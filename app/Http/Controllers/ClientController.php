<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\CartService;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function __construct(protected CartService $carts) {}

     public function index(){
        $products = Product::where('actif', '=', 1)->take(8)->get();
        $user = auth()->user();
        $carts = [];
        if($user){
            $carts = $user->carts()->with('products')->get();
        }

        return view('client.dashboard', compact('products','carts'));
    }
    public function getProducts(){
        $products = Product::where('actif', '=', 1)->get();
        $cartItems = $this->carts->getItems(auth()->id())->map(function ($item) {
            return [
                'id' => $item->product_id,
                'name' => $item->product?->title ?? 'Produit supprimé',
                'price' => (float) ($item->product?->price ?? 0),
                'description' => $item->product?->description ?? '',
                'quantity' => $item->quantity,
            ];
        })->values();

        return view('client/product.list', compact('products', 'cartItems'));
    }
} 

