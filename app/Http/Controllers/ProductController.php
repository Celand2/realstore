<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
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

    public function storeProduct(StoreProductRequest $request){
        $data = $request->validated();

        $imagePath = $request->file('image')->store('products', 'public');

        Product::create([
            'title' => $data['name'],
            'price' => $data['price'],
            'description' => $data['description'],
            'image' => $imagePath,
            'actif' => 1,
            'category_id' => $data['category'],
            'stock' => $data['stock'],
        ]);

        return redirect()->route('get-products')->with('status', 'Produit ajouté avec succès !');
    }

    public function editProduct($id){
        $product = Product::findOrFail($id);
        $categories = Category::all();
        return view('admin.product.edit', compact('product', 'categories'));
    }

    public function updateProduct(UpdateProductRequest $request, $id)
    {
        $product = Product::findOrFail($id);
        $data = $request->validated();

        if ($request->hasFile('image')) {
            // Supprimer l'ancienne image pour éviter l'accumulation
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }
            $product->image = $request->file('image')->store('products', 'public');
        }

        $product->update([
            'title' => $data['name'],
            'price' => $data['price'],
            'description' => $data['description'],
            'category_id' => $data['category'],
            'stock' => $data['stock'],
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
