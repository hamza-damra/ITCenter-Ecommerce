<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Offer;
use App\Models\PromotionalOffer;
use App\Models\CartItem;
use App\Models\Banner;
use App\Models\PromotionalAd;
use App\Models\HomeSection;
use App\Services\CartCacheService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{
    protected $cartCache;

    public function __construct(CartCacheService $cartCache)
    {
        $this->cartCache = $cartCache;
    }
    public function index(Request $request)
    {
        // Allow bypassing cache with ?nocache=1 parameter (for debugging/force refresh)
        $forceRefresh = $request->has('nocache') && $request->get('nocache') == '1';
        
        // Cache key for home page data
        $cacheKey = 'home_page_data_' . app()->getLocale();
        
        // Clear cache if force refresh is requested
        if ($forceRefresh) {
            Cache::forget($cacheKey);
            // Also clear for all locales to be thorough
            foreach (['ar', 'en', 'he'] as $locale) {
                Cache::forget("home_page_data_{$locale}");
            }
            Log::info('Home page cache cleared via nocache parameter', [
                'locale' => app()->getLocale(),
                'ip' => $request->ip()
            ]);
        }

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

            // Special Discounts & Offers (العروض والخصومات الخاصة)
            $specialDiscounts = Product::with(['brand:id,name_en,name_ar,name_he,slug', 'category:id,name_en,name_ar,name_he,slug'])
                ->select('id', 'name_en', 'name_ar', 'name_he', 'slug', 'price', 'sale_price', 'main_image', 'short_description_en', 'short_description_ar', 'short_description_he', 'is_new', 'is_featured', 'brand_id', 'category_id', 'stock_status')
                ->active()
                ->whereNotNull('sale_price')
                ->where('sale_price', '<', \DB::raw('price'))
                ->whereRaw('((price - sale_price) / price * 100) >= 10') // Minimum 10% discount
                ->orderByRaw('((price - sale_price) / price * 100) DESC') // Order by discount percentage
                ->limit(8)
                ->get();

            // Carousel categories - parent categories with display_mode 'carousel'
            $categories = Category::select('id', 'name_en', 'name_ar', 'name_he', 'slug', 'image', 'order', 'display_mode')
                ->active()
                ->parent()
                ->carousel()
                ->withCount([
                    'products' => function ($query) {
                        $query->active();
                    }
                ])
                ->orderBy('order')
                ->get();

            // Nav categories - parent categories with display_mode 'nav' and their children
            $navCategories = Category::select('id', 'name_en', 'name_ar', 'name_he', 'slug', 'icon', 'position', 'display_mode')
                ->active()
                ->parent()
                ->nav()
                ->with([
                    'children' => function ($query) {
                        $query->select('id', 'name_en', 'name_ar', 'name_he', 'slug', 'parent_id', 'position')
                            ->active()
                            ->orderBy('position');
                    }
                ])
                ->orderBy('position')
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

            $promotionalOffers = PromotionalOffer::with([
                'product' => function ($query) {
                    $query->select('id', 'name_en', 'name_ar', 'name_he', 'slug', 'main_image', 'stock_status');
                }
            ])
                ->select('id', 'product_id', 'title_en', 'title_ar', 'title_he', 'original_price', 'sale_price', 'discount_percentage', 'features_en', 'features_ar', 'features_he', 'start_date', 'end_date', 'display_order')
                ->active()
                ->orderBy('display_order')
                ->limit(3)
                ->get();

            // Special Offer Products - Get ALL products marked as special offer
            $specialOfferProducts = Product::with(['brand:id,name_en,name_ar,name_he,slug', 'category:id,name_en,name_ar,name_he,slug'])
                ->select('id', 'name_en', 'name_ar', 'name_he', 'slug', 'price', 'sale_price', 'main_image', 'short_description_en', 'short_description_ar', 'short_description_he', 'is_new', 'is_featured', 'brand_id', 'category_id', 'stock_status')
                ->active()
                ->where('is_special_offer', true)
                ->get();

            // Gift Ideas Section - Featured products as fallback
            $giftIdeas = Product::with(['brand:id,name_en,name_ar,name_he,slug', 'category:id,name_en,name_ar,name_he,slug'])
                ->select('id', 'name_en', 'name_ar', 'name_he', 'slug', 'price', 'sale_price', 'main_image', 'short_description_en', 'short_description_ar', 'short_description_he', 'is_new', 'is_featured', 'brand_id', 'category_id', 'stock_status')
                ->active()
                ->when(config('site.gift_ids'), function ($query) {
                    return $query->whereIn('id', config('site.gift_ids'));
                }, function ($query) {
                    return $query->featured();
                })
                ->latest()
                ->limit(2)
                ->get();

            // Dynamic Banners - Active banners ordered by display_order and created_at
            $banners = Banner::active()
                ->ordered()
                ->get();

            // Promotional Ads - Active ads grouped by position (left/right)
            // Uses the most recently updated active ad for each position
            $promotionalAds = PromotionalAd::active()
                ->orderBy('updated_at', 'desc')
                ->get()
                ->keyBy('position');

            return [
                'featuredProducts' => $featuredProducts,
                'newProducts' => $newProducts,
                'bestsellerProducts' => $bestsellerProducts,
                'onSaleProducts' => $onSaleProducts,
                'specialDiscounts' => $specialDiscounts,
                'categories' => $categories,
                'navCategories' => $navCategories,
                'featuredBrands' => $featuredBrands,
                'activeOffers' => $activeOffers,
                'promotionalOffers' => $promotionalOffers,
                'specialOfferProducts' => $specialOfferProducts,
                'giftIdeas' => $giftIdeas,
                'banners' => $banners,
                'promotionalAds' => $promotionalAds,
            ];
        });

        // Pick random special offer product on each page load (outside cache)
        $specialOfferProduct = null;
        $specialOfferProducts = $data['specialOfferProducts'] ?? collect();
        if ($specialOfferProducts->count() > 0) {
            $specialOfferProduct = $specialOfferProducts->random();
        }

        $data['specialOfferProduct'] = $specialOfferProduct;

        // Get cart product IDs for current user/session (not cached as it's user-specific)
        $cartProductIds = $this->getCartProductIds();

        // Get home sections ordered by display_order (not cached - lightweight query)
        // Wrapped in try/catch for safety before migration is run
        try {
            $homeSections = HomeSection::active()->ordered()->get();
        } catch (\Exception $e) {
            $homeSections = collect();
        }

        return view('home', array_merge($data, [
            'cartProductIds' => $cartProductIds,
            'specialOfferProducts' => $specialOfferProducts,
            'homeSections' => $homeSections,
        ]));
    }

    /**
     * Get cart product IDs for current user/session
     */
    private function getCartProductIds()
    {
        $identifier = $this->getCartIdentifier();

        // Use cached cart data to reduce database queries
        try {
            return $this->cartCache->getProductIds($identifier);
        } catch (\Exception $e) {
            // Fallback to direct database query if cache fails
            return CartItem::where(function ($query) use ($identifier) {
                if (isset($identifier['user_id'])) {
                    $query->where('user_id', $identifier['user_id']);
                } else {
                    $query->where('session_id', $identifier['session_id']);
                }
            })->pluck('product_id')->toArray();
        }
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
     * Clear home page cache - Enhanced version
     * Clears all caches and forces fresh data load
     */
    public function clearHomeCache()
    {
        try {
            // Use HomeCacheService for consistent cache clearing
            \App\Services\HomeCacheService::clearAll();
            
            // Also clear the entire cache table if using database driver
            if (config('cache.default') === 'database') {
                \DB::table('cache')->truncate();
            }
            
            // Clear compiled views
            \Artisan::call('view:clear');
            
            return response()->json([
                'success' => true, 
                'message' => 'Home page cache cleared successfully. Please refresh the page.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false, 
                'message' => 'Failed to clear cache: ' . $e->getMessage()
            ], 500);
        }
    }
}
