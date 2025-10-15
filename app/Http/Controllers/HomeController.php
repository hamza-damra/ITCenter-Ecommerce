<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Offer;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $featuredProducts = Product::with(['brand', 'category', 'images'])
            ->active()
            ->featured()
            ->limit(8)
            ->get();

        $newProducts = Product::with(['brand', 'category', 'images'])
            ->active()
            ->new()
            ->limit(8)
            ->get();

        $bestsellerProducts = Product::with(['brand', 'category', 'images'])
            ->active()
            ->bestseller()
            ->limit(8)
            ->get();

        $onSaleProducts = Product::with(['brand', 'category', 'images'])
            ->active()
            ->whereNotNull('sale_price')
            ->where('sale_price', '<', \DB::raw('price'))
            ->limit(8)
            ->get();

        $categories = Category::active()
            ->parent()
            ->withCount('products')
            ->orderBy('order')
            ->limit(10)
            ->get();

        $featuredBrands = Brand::active()
            ->featured()
            ->orderBy('order')
            ->limit(12)
            ->get();

        $activeOffers = Offer::active()
            ->limit(3)
            ->get();

        return view('home', compact(
            'featuredProducts',
            'newProducts',
            'bestsellerProducts',
            'onSaleProducts',
            'categories',
            'featuredBrands',
            'activeOffers'
        ));
    }
}
