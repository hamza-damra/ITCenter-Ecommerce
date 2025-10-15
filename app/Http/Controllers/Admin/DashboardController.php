<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Offer;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_products' => Product::count(),
            'active_products' => Product::active()->count(),
            'total_categories' => Category::count(),
            'total_brands' => Brand::count(),
            'featured_products' => Product::featured()->count(),
            'out_of_stock' => Product::where('stock_status', 'out_of_stock')->count(),
            'total_reviews' => \App\Models\Review::count(),
            'active_offers' => Offer::active()->count(),
        ];

        $recent_products = Product::with(['category', 'brand'])
            ->latest()
            ->limit(10)
            ->get();

        return view('admin.dashboard', compact('stats', 'recent_products'));
    }
}
