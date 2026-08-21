<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\CartProduct;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function addToCart(Request $request)
    {
        $request->validate([
            'products' => 'required|array|min:1',
            'products.*.id' => 'required|integer|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
        ]);

        // Toujours utiliser l'utilisateur authentifié, jamais le userID envoyé par le client
        $userId = Auth::id();
        if (!$userId) {
            return response()->json([
                'message' => 'Vous devez être connecté pour ajouter au panier.'
            ], 401);
        }

        // Récupérer ou créer le panier actif pour l'utilisateur
        $cart = Cart::firstOrCreate(['user_id' => $userId]);

        foreach ($request->products as $productData) {
            $product = Product::findOrFail($productData['id']);
            $existing = CartProduct::where('cart_id', $cart->id)
                        ->where('product_id', $product->id)
                        ->first();
            $quantity = ($existing?->quantity ?? 0) + $productData['quantity'];

            if ($quantity > $product->stock) {
                return response()->json([
                    'message' => "Stock insuffisant pour {$product->title}."
                ], 422);
            }

            if ($existing) {
                $existing->quantity = $quantity;
                $existing->save();
            } else {
                CartProduct::create([
                    'cart_id' => $cart->id,
                    'product_id' => $product->id,
                    'quantity' => $productData['quantity'],
                ]);
            }
        }

        return response()->json([
            'message' => 'Panier enregistré avec succès !'
        ], 200);
    }

    // Affiche le panier de l'utilisateur connecté
    public function index(Request $request)
    {
        $user = $request->user();
        $cart = Cart::where('user_id', $user->id)->latest()->first();
        $items = [];

        if ($cart) {
            $items = CartProduct::where('cart_id', $cart->id)->with('product')->get();
        }

        return view('cart.index', ['items' => $items, 'cart' => $cart]);
    }

    // Met à jour la quantité d'un item
    public function updateItem(Request $request)
    {
        $request->validate([
            'cart_item_id' => 'required|integer',
            'quantity' => 'required|integer|min:1',
        ]);

        $item = CartProduct::whereHas('cart', function ($query) use ($request) {
            $query->where('user_id', $request->user()->id);
        })->with('product')->findOrFail($request->cart_item_id);

        if ($request->quantity > $item->product->stock) {
            return redirect()->back()->with('error', 'La quantité demandée dépasse le stock disponible.');
        }

        $item->quantity = $request->quantity;
        $item->save();

        return redirect()->back()->with('status', 'Quantité mise à jour');
    }

    // Supprime un item du panier
    public function removeItem(Request $request)
    {
        $request->validate([
            'cart_item_id' => 'required|integer',
        ]);

        $item = CartProduct::whereHas('cart', function ($query) use ($request) {
            $query->where('user_id', $request->user()->id);
        })->findOrFail($request->cart_item_id);
        $item->delete();

        return redirect()->back()->with('status', 'Article supprimé');
    }

    // Affiche la page de checkout
    public function checkout(Request $request)
    {
        $user = $request->user();
        $cart = Cart::where('user_id', $user->id)->latest()->first();
        $items = [];

        if ($cart) {
            $items = CartProduct::where('cart_id', $cart->id)->with('product')->get();
        }

        $total = $items->reduce(function($carry, $item){
            return $carry + ($item->product->price * $item->quantity);
        }, 0);

        return view('cart.checkout', compact('items','total'));
    }

    // Procède au paiement simple : crée une commande et vide le panier
    public function processCheckout(Request $request)
    {
        $request->validate(['address' => 'required|string']);

        $user = $request->user();
        $cart = Cart::where('user_id', $user->id)->latest()->first();
        $cartItems = $cart
            ? CartProduct::where('cart_id', $cart->id)->with('product')->get()
            : collect();

        if ($cartItems->isEmpty()) {
            return redirect()->route('client-get-product')->with('error', 'Votre panier est vide.');
        }

        foreach ($cartItems as $cartItem) {
            if (!$cartItem->product || $cartItem->quantity > $cartItem->product->stock) {
                return redirect()->route('cart.index')->with('error', 'Le stock de l’un des produits a changé.');
            }
        }

        $total = $cartItems->sum(fn ($item) => $item->product->price * $item->quantity);

        DB::beginTransaction();
        try {
            $order = Order::create([
                'user_id' => $user->id,
                'total' => $total,
                'status' => 'pending',
                'address' => $request->address,
            ]);

            foreach ($cartItems as $cartItem) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cartItem->product_id,
                    'quantity' => $cartItem->quantity,
                    'price' => $cartItem->product->price,
                ]);

                $cartItem->product->decrement('stock', $cartItem->quantity);
            }

            CartProduct::where('cart_id', $cart->id)->delete();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Erreur lors de la création de la commande');
        }

        return redirect()->route('orders.show', $order->id)->with('status', 'Commande créée');
    }
}
