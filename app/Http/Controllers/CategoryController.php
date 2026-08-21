<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function getCategories(){
        $categories = Category::all();
        return view('admin.category.list', compact('categories'));
    }

    public function addCategory(Request $request){
        $validateCategory =$request->validate([
            'name'=>'required|string'
        ]);

        Category::create($validateCategory);
        return redirect()->route('list-categories')->with('status','la categorie a été ajouté avec succès !');
    }

    public function deleteCategory($id){
        $category = Category::find($id);

        $category->delete();
        return redirect()->route('list-categories')->with('status','la categorie a été supprimé avec succès !');
    }

    public function editCategory($id){
        $category = Category::findOrFail($id);
        return view('admin.category.edit', compact('category'));
    }

    public function updateCategory(Request $request, $id){
        $category = Category::findOrFail($id);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $category->update($data);

        return redirect()->route('list-categories')->with('status', 'La catégorie a été modifiée avec succès !');
    }
}
