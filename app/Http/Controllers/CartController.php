<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;

class CartController extends Controller
{
    public function addToCart(Request $request)
    {
        // Vérification des données reçues
        $request->validate([
            'userID' => 'required|integer',
            'products' => 'required|array|min:1',
        ]);

        foreach ($request->products as $product) { 

            Cart::create([
                'user_id' => $request->userID,
                'product_id' => $product['id'],
                'quantity' => $product['quantity'],
                'price' => $product['price'],
            ]);
        }

        return response()->json([
            'message' => 'Panier enregistré avec succès !'
        ], 200);
    }
}
