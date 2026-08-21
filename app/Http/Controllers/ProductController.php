<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function getProducts(){
        $products = Product::with('category')->get();
        return view('admin.product.listProduct', compact('products'));
    }

    public function addProduct(){
        $categories = Category::all();
        return view('admin.product.addProduct', compact('categories'));
    }

    public function storeProduct(Request $request){
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'description' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'category' => 'required|numeric',
            'stock' => 'required|integer|min:0',
        ]);

        $imagePath = $request->file('image')->store('products', 'public');

        Product::create([
            'title' => $request->name,
            'price' => $request->price,
            'description' => $request->description,
            'image' => $imagePath,
            'actif' => 1,
            'category_id' => $request->category,
            'stock' => $request->stock,
        ]);

        return redirect()->route('get-products')->with('status', 'Produit ajouté avec succès !');
    }

    public function editProduct($id){
        $product = Product::findOrFail($id);
        $categories = Category::all();
        return view('admin.product.edit', compact('product', 'categories'));
    }

    public function updateProduct(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'description' => 'required|string',
            'category' => 'required|numeric',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($request->hasFile('image')) {
            // Supprimer l'ancienne image pour éviter l'accumulation
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }
            $imagePath = $request->file('image')->store('products', 'public');
            $product->image = $imagePath;
        }

        $product->update([
            'title' => $request->name,
            'price' => $request->price,
            'description' => $request->description,
            'category_id' => $request->category,
            'stock' => $request->stock,
        ]);

        return redirect()->route('get-products')->with('status', 'Produit modifié avec succès !');
    }

    public function showProduct($id)
    {
        $product = Product::with('category')->findOrFail($id);
        return view('admin.product.show', compact('product'));
    }

    public function deleteProduct($id)
    {
        $product = Product::findOrFail($id);

        // Supprimer l'image associée si elle existe
        if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect()->route('get-products')->with('status', 'Produit supprimé avec succès !');
    }
}
