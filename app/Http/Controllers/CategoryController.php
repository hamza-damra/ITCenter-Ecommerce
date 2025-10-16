<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

/**
 * Web Controller - Returns views only
 * All business logic moved to API controllers
 */
class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('products')
            ->orderBy('name_en')
            ->get();
            
        return view('categories', compact('categories'));
    }

    public function show($slug)
    {
        $category = Category::where('slug', $slug)->firstOrFail();
        
        return view('category-products', compact('slug', 'category'));
    }
}
