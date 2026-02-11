<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'market')->name('home');

Route::view('/panier', 'cart')->name('cart');

Route::controller(LoginController::class)->name('login.')->group(function () {
    Route::get('connexion', 'show')->name('show');
    Route::post('connexion/login', 'login')->name('login');
    Route::post('connexion/logout', 'logout')->name('logout');
});

Route::controller(LoginController::class)->name('register.')->group(function () {
    Route::get('inscription', 'showRegister')->name('show');
    Route::post('inscription', 'register')->name('store');
});

Route::middleware('auth')->group(function () {

    Route::view('/catégories', 'categories')->name('categories');
    
    Route::controller(ProductController::class)->name('products.')->group(function () {
        Route::get('produits', 'index')->name('index');
        Route::get('produits/{product}/infos', 'edit_infos')->name('edit.infos');
        Route::get('produits/{product}/categories', 'edit_categories')->name('edit.categories');
        Route::get('produits/{product}/variants', 'edit_variants')->name('edit.variants');
        Route::get('produits/{product}/images', 'edit_images')->name('edit.images');
    });

});