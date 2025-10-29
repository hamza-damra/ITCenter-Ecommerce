<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Offer;
use App\Models\PromotionalOffer;
use App\Models\CartItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    public function index()
    {
        // Cache key for home page data
        $cacheKey = 'home_page_data_' . app()->getLocale();
        
        // Try to get data from cache first (cache for 30 minutes)
        $data = Cache::remember($cacheKey, 1800, function () {
            // Optimize queries with eager loading - only fetch non-empty collections
            
            // Featured Products (المنتجات المميزة)
            $featuredProducts = Product::with(['brand:id,name_en,name_ar,name_he,slug', 'category:id,name_en,name_ar,name_he,slug'])
                ->select('id', 'name_en', 'name_ar', 'name_he', 'slug', 'price', 'sale_price', 'main_image', 'short_description_en', 'short_description_ar', 'short_description_he', 'is_new', 'is_featured', 'brand_id', 'category_id', 'stock_status')
                ->active()
                ->featured()
                ->limit(8)
                ->get();

            // New Arrivals (وصل حديثاً)
            $newProducts = Product::with(['brand:id,name_en,name_ar,name_he,slug', 'category:id,name_en,name_ar,name_he,slug'])
                ->select('id', 'name_en', 'name_ar', 'name_he', 'slug', 'price', 'sale_price', 'main_image', 'short_description_en', 'short_description_ar', 'short_description_he', 'is_new', 'is_featured', 'brand_id', 'category_id', 'stock_status')
                ->active()
                ->new()
                ->limit(8)
                ->get();

            // Bestsellers (الأكثر مبيعاً)
            $bestsellerProducts = Product::with(['brand:id,name_en,name_ar,name_he,slug', 'category:id,name_en,name_ar,name_he,slug'])
                ->select('id', 'name_en', 'name_ar', 'name_he', 'slug', 'price', 'sale_price', 'main_image', 'short_description_en', 'short_description_ar', 'short_description_he', 'is_new', 'is_featured', 'brand_id', 'category_id', 'stock_status')
                ->active()
                ->bestseller()
                ->limit(8)
                ->get();

            // On Sale Products (التخفيضات)
            $onSaleProducts = Product::with(['brand:id,name_en,name_ar,name_he,slug', 'category:id,name_en,name_ar,name_he,slug'])
                ->select('id', 'name_en', 'name_ar', 'name_he', 'slug', 'price', 'sale_price', 'main_image', 'short_description_en', 'short_description_ar', 'short_description_he', 'is_new', 'is_featured', 'brand_id', 'category_id', 'stock_status')
                ->active()
                ->whereNotNull('sale_price')
                ->where('sale_price', '<', \DB::raw('price'))
                ->limit(8)
                ->get();

            $categories = Category::select('id', 'name_en', 'name_ar', 'name_he', 'slug', 'image', 'order')
                ->active()
                ->parent()
                ->withCount(['products' => function($query) {
                    $query->active();
                }])
                ->orderBy('order')
                ->get();

            $featuredBrands = Brand::select('id', 'name_en', 'name_ar', 'name_he', 'slug', 'logo', 'order')
                ->active()
                ->featured()
                ->orderBy('order')
                ->limit(12)
                ->get();

            $activeOffers = Offer::select('id', 'name_en', 'name_ar', 'slug', 'description_en', 'description_ar', 'discount_type', 'discount_value', 'start_date', 'end_date', 'banner_image')
                ->active()
                ->limit(3)
                ->get();

            $promotionalOffers = PromotionalOffer::with(['product' => function($query) {
                    $query->select('id', 'name_en', 'name_ar', 'name_he', 'slug', 'main_image', 'stock_status');
                }])
                ->select('id', 'product_id', 'title_en', 'title_ar', 'title_he', 'original_price', 'sale_price', 'discount_percentage', 'features_en', 'features_ar', 'features_he', 'start_date', 'end_date', 'display_order')
                ->active()
                ->orderBy('display_order')
                ->limit(3)
                ->get();

            return [
                'featuredProducts' => $featuredProducts,
                'newProducts' => $newProducts,
                'bestsellerProducts' => $bestsellerProducts,
                'onSaleProducts' => $onSaleProducts,
                'categories' => $categories,
                'featuredBrands' => $featuredBrands,
                'activeOffers' => $activeOffers,
                'promotionalOffers' => $promotionalOffers,
            ];
        });

        // Get cart product IDs for current user/session (not cached as it's user-specific)
        $cartProductIds = $this->getCartProductIds();

        return view('home', array_merge($data, [
            'cartProductIds' => $cartProductIds
        ]));
    }

    /**
     * Get cart product IDs for current user/session
     */
    private function getCartProductIds()
    {
        $identifier = $this->getCartIdentifier();

        return CartItem::where(function($query) use ($identifier) {
            if (isset($identifier['user_id'])) {
                $query->where('user_id', $identifier['user_id']);
            } else {
                $query->where('session_id', $identifier['session_id']);
            }
        })->pluck('product_id')->toArray();
    }

    /**
     * Get cart identifier (user_id or session_id)
     */
    private function getCartIdentifier()
    {
        if (Auth::check()) {
            return ['user_id' => Auth::id()];
        }

        // Ensure session is started
        if (!Session::isStarted()) {
            Session::start();
        }

        return ['session_id' => Session::getId()];
    }

    /**
     * Clear home page cache
     */
    public function clearHomeCache()
    {
        Cache::forget('home_page_data_ar');
        Cache::forget('home_page_data_en');
        Cache::forget('home_page_data_he');
        
        return response()->json(['success' => true, 'message' => 'Cache cleared successfully']);
    }
}
