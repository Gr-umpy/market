<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('market');
})->name('home');
Route::get('/catégories', function () {
    return view('category');
})->name('category');
Route::get('/panier', function () {
    return view('cart');
})->name('cart');