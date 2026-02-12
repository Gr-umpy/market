<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('product.index');
    }
    
    public function edit_infos(Product $product)
    {
        return view('product.edit.infos', ['product' => $product]);
    }
    
    public function edit_categories(Product $product)
    {
        return view('product.edit.categories', ['product' => $product]);
    }
    
    public function edit_variants(Product $product)
    {
        return view('product.edit.variants', ['product' => $product]);
    }
    
    public function edit_images(Product $product)
    {
        return view('product.edit.images', ['product' => $product]);
    }

    public function show(Product $product)
    {
        return view('product.show', compact('product'));
    }

}
