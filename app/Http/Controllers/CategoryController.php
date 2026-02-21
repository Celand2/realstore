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
}
