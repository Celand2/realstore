<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ClientController extends Controller
{
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
        return view('client/product.list' , compact('products'));               
    }
} 

