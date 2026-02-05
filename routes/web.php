<?php

use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'market')->name('home');
Route::view('/catégories', 'categories')->name('categories');
Route::view('/panier', 'cart')->name('cart');

Route::controller(LoginController::class)->name('login.')->group(function () {
        Route::get('connexion', 'show')->name('show');
        Route::post('connexion/login', 'login')->name('login');
        Route::post('connexion/logout', 'logout')->name('logout');
    });