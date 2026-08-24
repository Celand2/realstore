<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckoutRequest;
use App\Models\Cart;
use App\Models\CartProduct;
use App\Services\CartService;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function __construct(
        protected CartService $carts,
        protected OrderService $orders,
    ) {}

    public function addToCart(Request $request)
    {
        $request->validate([
            'products' => 'required|array|min:1',
            'products.*.id' => 'required|integer|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
            'sync' => 'sometimes|boolean',
        ]);

        $userId = Auth::id();
        if (!$userId) {
            return response()->json(['message' => 'Vous devez être connecté.'], 401);
        }

        try {
            $this->carts->addProducts(
                $userId,
                $request->input('products'),
                $request->boolean('sync'),
            );
        } catch (\RuntimeException $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }

        if (!$request->expectsJson()) {
            return redirect()->route('cart.index')->with('status', 'Produit ajouté au panier.');
        }

        return response()->json(['message' => 'Panier enregistré avec succès !'], 200);
    }

    public function index(Request $request)
    {
        $user = $request->user();
        $cart = Cart::where('user_id', $user->id)->latest()->first();
        $items = $cart
            ? CartProduct::where('cart_id', $cart->id)->with('product')->get()
            : collect();

        return view('cart.index', ['items' => $items, 'cart' => $cart]);
    }

    public function updateItem(Request $request)
    {
        $data = $request->validate([
            'cart_item_id' => 'required|integer',
            'quantity' => 'required|integer|min:1',
        ]);

        try {
            $this->carts->updateItem(
                $request->user()->id,
                $data['cart_item_id'],
                $data['quantity'],
            );
        } catch (\RuntimeException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }

        return redirect()->back()->with('status', 'Quantité mise à jour');
    }

    public function removeItem(Request $request)
    {
        $data = $request->validate([
            'cart_item_id' => 'required|integer',
        ]);

        $this->carts->removeItem($request->user()->id, $data['cart_item_id']);

        return redirect()->back()->with('status', 'Article supprimé');
    }

    public function checkout(Request $request)
    {
        $items = $this->carts->getItems($request->user()->id);
        $total = $this->carts->total($items);

        return view('cart.checkout', compact('items', 'total'));
    }

    public function processCheckout(CheckoutRequest $request)
    {
        try {
            $order = $this->orders->checkout(
                $request->user()->id,
                $request->input('address'),
            );
        } catch (\RuntimeException $e) {
            return redirect()->route('cart.index')->with('error', $e->getMessage());
        }

        return redirect()->route('orders.show', \Vinkla\Hashids\Facades\Hashids::encode($order->id))->with('status', 'Commande créée');
    }
}
