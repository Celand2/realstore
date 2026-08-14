<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\CartProduct;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use App\Models\OrderItem;

class CartController extends Controller
{
    public function addToCart(Request $request)
    {
        // Vérification des données reçues
        $request->validate([
            'userID' => 'required|integer',
            'products' => 'required|array|min:1',
        ]);

        // Récupérer ou créer le panier actif pour l'utilisateur
        $cart = Cart::firstOrCreate(['user_id' => $request->userID]);

        foreach ($request->products as $product) {
            $existing = CartProduct::where('cart_id', $cart->id)
                        ->where('product_id', $product['id'])
                        ->first();

            if ($existing) {
                $existing->quantity += $product['quantity'];
                $existing->save();
            } else {
                CartProduct::create([
                    'cart_id' => $cart->id,
                    'product_id' => $product['id'],
                    'quantity' => $product['quantity'],
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

        $item = CartProduct::findOrFail($request->cart_item_id);
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

        $item = CartProduct::findOrFail($request->cart_item_id);
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
        $request->validate([
            'address' => 'required|string',
            'total' => 'required|numeric'
        ]);

        $user = $request->user();
        $cart = Cart::where('user_id', $user->id)->latest()->first();

        DB::beginTransaction();
        try {
            $order = Order::create([
                'user_id' => $user->id,
                'total' => $request->total,
                'status' => 'pending',
                'address' => $request->address,
            ]);

            // Transférer les items du panier vers order_items
            if ($cart) {
                $cartItems = CartProduct::where('cart_id', $cart->id)->with('product')->get();
                foreach ($cartItems as $ci) {
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $ci->product_id,
                        'quantity' => $ci->quantity,
                        'price' => $ci->product ? $ci->product->price : 0,
                    ]);
                }

                // vider les lignes du panier
                \App\Models\CartProduct::where('cart_id', $cart->id)->delete();
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Erreur lors de la création de la commande');
        }

        return redirect()->route('orders.show', $order->id)->with('status', 'Commande créée');
    }
}
