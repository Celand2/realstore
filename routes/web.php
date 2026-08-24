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

Route::get('/', [VisitorController::class, 'home'])->name('home');
Route::get('/about', [VisitorController::class, 'about'])->name('about');
Route::get('/products', [VisitorController::class, 'products'])->name('products');

//Les routes qui passent par l'authentification
Route::middleware('auth')->group(function (){
    // Les routes pour les admins
    Route::prefix('admin')->middleware('role:admin')->group(function (){
        Route::get('/dashboard',[DashboardController::class,'index']);
        Route::get('/categories',[CategoryController::class,'getCategories'])->name('list-categories');
        Route::post('/add-category',[CategoryController::class,'addCategory'])->name('add-category');
        Route::get('/edit-category/{id}',[CategoryController::class,'editCategory'])->name('edit-category');
        Route::put('/update-category/{id}',[CategoryController::class,'updateCategory'])->name('update-category');
        Route::get('/delete-category/{id}',[CategoryController::class,'deleteCategory'])->name('delete-category');
        Route::get('/products',[ProductController::class,'getProducts'])->name('get-products');
        Route::get('/add-product',[ProductController::class,'addProduct'])->name('add-product');
        Route::post('/store-product',[ProductController::class,'storeProduct'])->name('store-product');
        Route::get('/edit-product/{id}',[ProductController::class,'editProduct'])->name('edit-product');
        Route::put('/update-product/{id}', [ProductController::class, 'updateProduct'])->name('update-product');
        Route::get('/show-product/{id}', [ProductController::class, 'showProduct'])->name('show-product');
        Route::delete('/delete-product/{id}', [ProductController::class, 'deleteProduct'])->name('delete-product');

        // Admin orders
        Route::get('/orders', [App\Http\Controllers\Admin\OrderController::class, 'index'])->name('admin.orders.index');
        Route::get('/orders/{id}', [App\Http\Controllers\Admin\OrderController::class, 'show'])->name('admin.orders.show');
        Route::post('/orders/{id}/status', [App\Http\Controllers\Admin\OrderController::class, 'updateStatus'])->name('admin.orders.updateStatus');

        // Admin users
        Route::get('/users', [App\Http\Controllers\Admin\UserController::class, 'index'])->name('admin.users.index');
        Route::get('/users/create', [App\Http\Controllers\Admin\UserController::class, 'create'])->name('admin.users.create');
        Route::post('/users', [App\Http\Controllers\Admin\UserController::class, 'store'])->name('admin.users.store');
        Route::get('/users/{id}/edit', [App\Http\Controllers\Admin\UserController::class, 'edit'])->name('admin.users.edit');
        Route::put('/users/{id}', [App\Http\Controllers\Admin\UserController::class, 'update'])->name('admin.users.update');
        Route::delete('/users/{id}', [App\Http\Controllers\Admin\UserController::class, 'destroy'])->name('admin.users.destroy');



    });

    //Les routes pour les clients
    Route::prefix('client')->middleware('role:client')->group(function (){
        Route::get('/dashboard',[ClientController::class,'index']);
        Route::get('/products',[ClientController::class,'getProducts'])->name('client-get-product'); 

        Route::post('/add-cart', [CartController::class, 'addToCart'])->name('cart.add');
        Route::get('/cart', [App\Http\Controllers\CartController::class,'index'])->name('cart.index');
        Route::post('/cart/update', [App\Http\Controllers\CartController::class,'updateItem'])->name('cart.update');
        Route::post('/cart/remove', [App\Http\Controllers\CartController::class,'removeItem'])->name('cart.remove');
        Route::get('/cart/checkout', [App\Http\Controllers\CartController::class,'checkout'])->name('cart.checkout');
        Route::post('/cart/checkout', [App\Http\Controllers\CartController::class,'processCheckout'])->name('cart.process');

    });
    
    
});

// Profil utilisateur
Route::middleware('auth')->group(function(){
    Route::get('/profile',[App\Http\Controllers\ProfileController::class,'edit'])->name('profile.edit');
    Route::post('/profile',[App\Http\Controllers\ProfileController::class,'update'])->name('profile.update');
});

// Orders (client)
Route::prefix('client')->middleware(['auth','role:client'])->group(function(){
    Route::get('/orders',[App\Http\Controllers\OrderController::class,'index'])->name('orders.index');
    Route::get('/orders/{id}',[App\Http\Controllers\OrderController::class,'show'])->name('orders.show');
    Route::post('/orders',[App\Http\Controllers\OrderController::class,'store'])->name('orders.store');
    Route::post('/orders/{id}/cancel',[App\Http\Controllers\OrderController::class,'cancel'])->name('orders.cancel');
});

 