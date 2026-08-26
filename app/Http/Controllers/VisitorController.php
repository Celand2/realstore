<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class VisitorController extends Controller
{
    //Methode permet d'afficher la page d'accueil
    public function home(){
        return view('index');
    }

    //Methode permet d'afficher la page a propos
    public function about(){
        return view('about');
    }

    //Methode permet d'afficher la page contact
    public function products(Request $request){
        $categories = Category::orderBy('name')->get();
        $products = Product::with('category')
            ->where('actif', true)
            ->when($request->filled('q'), function ($query) use ($request) {
                $query->where(function ($query) use ($request) {
                    $query->where('title', 'like', '%' . $request->string('q') . '%')
                        ->orWhere('description', 'like', '%' . $request->string('q') . '%');
                });
            })
            ->when($request->filled('category'), fn ($query) => $query->where('category_id', $request->integer('category')))
            ->when($request->input('sort') === 'price_asc', fn ($query) => $query->orderBy('price'))
            ->when($request->input('sort') === 'price_desc', fn ($query) => $query->orderByDesc('price'))
            ->when(!$request->filled('sort'), fn ($query) => $query->latest())
            ->paginate(12)
            ->withQueryString();

        return view('products', compact('products', 'categories'));
    }

    public function showProduct($id){
        $product = Product::with(['category', 'carts'])
            ->where('actif', true)
            ->findOrFail($id);
        $relatedProducts = Product::where('actif', true)
            ->where('category_id', $product->category_id)
            ->whereKeyNot($product->id)
            ->latest()
            ->take(4)
            ->get();

        return view('products.show', compact('product', 'relatedProducts'));
    }
}
