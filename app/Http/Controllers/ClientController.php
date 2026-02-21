<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ClientController extends Controller
{
     public function index(){
        return view('client/dashboard'); 
    }
    public function getProducts(){
        $products = Product::where('actif', '=', 1)->get();
        return view('client/product.list' , compact('products'));               
    }
} 

