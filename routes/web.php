<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VisitorController;
use App\Http\Controllers\CartController;
use App\Http\Middleware\CheckProfile;


// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/',[VisitorController::class,'home']);
Route::get('/about',[VisitorController::class,'about']);
Route::get('/products',[VisitorController::class,'products']);

//Les routes qui passent par l'authentification
Route::middleware('auth')->group(function (){
    // Les routes pour les admins
    Route::prefix('admin')->middleware('role:admin')->group(function (){
        Route::get('/dashboard',[DashboardController::class,'index']);
        Route::get('/categories',[CategoryController::class,'getCategories'])->name('list-categories');
        Route::post('/add-category',[CategoryController::class,'addCategory'])->name('add-category');
        Route::get('/delete-category/{id}',[CategoryController::class,'deleteCategory'])->name('delete-category');
        Route::get('/products',[ProductController::class,'getProducts'])->name('get-products');
        Route::get('/add-product',[ProductController::class,'addProduct'])->name('add-product');
        Route::post('/store-product',[ProductController::class,'storeProduct'])->name('store-product');
        Route::get('/edit-product/{id}',[ProductController::class,'editProduct'])->name('edit-product');
        Route::put('/update-product/{id}', [ProductController::class, 'updateProduct'])->name('update-product');



    });

    //Les routes pour les clients
    Route::prefix('client')->middleware('role:client')->group(function (){
        Route::get('/dashboard',[ClientController::class,'index']);
        Route::get('/products',[ClientController::class,'getProducts'])->name('client-get-product'); 

        Route::post('/add-cart', [CartController::class, 'addToCart']);

    });
    
    
});

// Profil utilisateur
Route::middleware('auth')->group(function(){
    Route::get('/profile',[App\Http\Controllers\ProfileController::class,'edit'])->name('profile.edit');
    Route::post('/profile',[App\Http\Controllers\ProfileController::class,'update'])->name('profile.update');
});

 