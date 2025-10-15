<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::with(['children'])
            ->active()
            ->parent()
            ->withCount('products')
            ->orderBy('order')
            ->get();

        return view('categories', compact('categories'));
    }

    public function show($slug)
    {
        $category = Category::with(['children'])
            ->where('slug', $slug)
            ->active()
            ->firstOrFail();

        $products = Product::with(['brand', 'images'])
            ->where('category_id', $category->id)
            ->active()
            ->paginate(12);

        return view('category-products', compact('category', 'products'));
    }
}
