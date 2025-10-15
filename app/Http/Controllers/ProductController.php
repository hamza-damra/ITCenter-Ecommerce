<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        return view('products');
    }

    public function show($id)
    {
        // Here you can fetch the product from database using $id
        // For now, we'll just return the view
        return view('product-detail');
    }
}
