<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Offer;
use App\Models\Review;
use App\Models\User;
use App\Models\CartItem;
use App\Models\Favorite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Advanced SQL queries for comprehensive dashboard statistics
        $stats = [
            // Product Statistics
            'total_products' => Product::count(),
            'active_products' => Product::active()->count(),
            'featured_products' => Product::featured()->count(),
            
            // Stock Management with advanced queries
            'out_of_stock' => Product::where('stock_status', 'out_of_stock')->count(),
            'low_stock' => Product::where('stock_status', 'low_stock')->count(),
            'in_stock' => Product::where('stock_status', 'in_stock')->count(),
            
            // Category & Brand Statistics
            'total_categories' => Category::count(),
            'active_categories' => Category::where('is_active', true)->count(),
            'total_brands' => Brand::count(),
            'active_brands' => Brand::where('is_active', true)->count(),
            
            // Review & Rating Statistics with advanced aggregations
            'total_reviews' => Review::count(),
            'average_rating' => round(Review::avg('rating') ?? 0, 1),
            'pending_reviews' => Review::where('is_approved', false)->count(),
            'approved_reviews' => Review::where('is_approved', true)->count(),
            
            // Most reviewed products
            'most_reviewed_product' => Product::withCount('reviews')
                ->having('reviews_count', '>', 0)
                ->orderByDesc('reviews_count')
                ->first(),
            
            // Offer Statistics
            'active_offers' => Offer::active()->count(),
            'total_offers' => Offer::count(),
            'expired_offers' => Offer::where('end_date', '<', now())->count(),
            
            // Products with offers (using JOIN)
            'products_on_offer' => DB::table('products')
                ->join('product_offers', 'products.id', '=', 'product_offers.product_id')
                ->join('offers', 'product_offers.offer_id', '=', 'offers.id')
                ->where('offers.is_active', true)
                ->where('offers.start_date', '<=', now())
                ->where('offers.end_date', '>=', now())
                ->distinct('products.id')
                ->count('products.id'),
            
            // User & Customer Statistics
            'total_users' => User::count(),
            'users_this_month' => User::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),
            
            // Cart Statistics with advanced aggregations
            'total_cart_items' => CartItem::sum('quantity'),
            'active_carts' => CartItem::distinct('session_id')->count('session_id'),
            'cart_value' => round(CartItem::selectRaw('SUM(quantity * price) as total')
                ->value('total') ?? 0, 2),
            
            // Favorite Statistics
            'total_favorites' => Favorite::count(),
            'most_favorited' => Product::withCount('favorites')
                ->having('favorites_count', '>', 0)
                ->orderByDesc('favorites_count')
                ->first(),
            
            // Price Analytics
            'avg_product_price' => round(Product::avg('price') ?? 0, 2),
            'highest_priced_product' => Product::orderByDesc('price')->first(),
            'lowest_priced_product' => Product::where('price', '>', 0)
                ->orderBy('price')
                ->first(),
            
            // Category Performance (products per category)
            'category_distribution' => Category::withCount('products')
                ->orderByDesc('products_count')
                ->limit(5)
                ->get(),
            
            // Brand Performance (products per brand)
            'brand_distribution' => Brand::withCount('products')
                ->orderByDesc('products_count')
                ->limit(5)
                ->get(),
            
            // Recent Activity Stats
            'products_added_today' => Product::whereDate('created_at', today())->count(),
            'products_added_this_week' => Product::whereBetween('created_at', [
                now()->startOfWeek(),
                now()->endOfWeek()
            ])->count(),
            'products_added_this_month' => Product::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),
            
            // Reviews added this month
            'reviews_this_month' => Review::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),
            
            // Stock Value Calculations
            'total_stock_value' => round(
                Product::selectRaw('SUM(price * stock_quantity) as value')
                    ->where('stock_status', '!=', 'out_of_stock')
                    ->value('value') ?? 0,
                2
            ),
            
            // Products needing attention (low stock or out of stock)
            'products_need_attention' => Product::whereIn('stock_status', ['low_stock', 'out_of_stock'])
                ->count(),
        ];

        // Recent products with relationships and ratings
        $recent_products = Product::with(['category', 'brand', 'reviews'])
            ->withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->latest()
            ->limit(10)
            ->get();

        // Top rated products
        $top_rated_products = Product::with(['category', 'brand'])
            ->withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->having('reviews_count', '>', 0)
            ->orderByDesc('reviews_avg_rating')
            ->limit(5)
            ->get();

        // Low stock alerts
        $low_stock_products = Product::with(['category', 'brand'])
            ->where('stock_status', 'low_stock')
            ->orWhere('stock_status', 'out_of_stock')
            ->orderBy('stock_quantity')
            ->limit(10)
            ->get();

        return view('admin.dashboard', compact(
            'stats',
            'recent_products',
            'top_rated_products',
            'low_stock_products'
        ));
    }
}
