<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function getCategories(){
        $categories = Category::all();
        return view('admin.category.list', compact('categories'));
    }

    public function addCategory(StoreCategoryRequest $request){
        Category::create($request->validated());
        return redirect()->route('list-categories')->with('status','La catégorie a été ajoutée avec succès !');
    }

    public function deleteCategory($id){
        $category = Category::findOrFail($id);
        $category->delete();
        return redirect()->route('list-categories')->with('status','La catégorie a été supprimée avec succès !');
    }

    public function editCategory($id){
        $category = Category::findOrFail($id);
        return view('admin.category.edit', compact('category'));
    }

    public function updateCategory(UpdateCategoryRequest $request, $id){
        $category = Category::findOrFail($id);
        $category->update($request->validated());

        return redirect()->route('list-categories')->with('status', 'La catégorie a été modifiée avec succès !');
    }
}
