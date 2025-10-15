<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Product;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    public function index()
    {
        $brands = Brand::active()
            ->withCount('products')
            ->orderBy('order')
            ->get();

        $featuredBrands = $brands->where('is_featured', true);

        return view('brands', compact('brands', 'featuredBrands'));
    }

    public function show($slug)
    {
        $brand = Brand::where('slug', $slug)
            ->active()
            ->firstOrFail();

        $products = Product::with(['category', 'images'])
            ->where('brand_id', $brand->id)
            ->active()
            ->paginate(12);

        return view('brand-products', compact('brand', 'products'));
    }
}
