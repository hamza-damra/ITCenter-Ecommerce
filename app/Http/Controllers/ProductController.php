<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\CartItem;
use App\Services\ProductFilterService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

/**
 * Web Controller - Returns views only
 * All business logic moved to API controllers
 */
class ProductController extends Controller
{
    protected $filterService;

    public function __construct(ProductFilterService $filterService)
    {
        $this->filterService = $filterService;
    }

    public function index(Request $request)
    {
        $query = Product::with(['category', 'brand', 'images'])
            ->active();

        // Apply all filters via ProductFilterService (categories, brands, tags, price, stock, dynamic, etc.)
        $query = $this->filterService->applyFilters($query, $request);

        // Filter by features
        if ($request->has('featured') && $request->featured) {
            $query->featured();
        }
        if ($request->has('new') && $request->new) {
            $query->new();
        }
        if ($request->has('bestseller') && $request->bestseller) {
            $query->bestseller();
        }

        // Filter by special offer
        if ($request->has('special_offer') && $request->special_offer) {
            $query->where('is_special_offer', true);
        }

        // Handle filter parameter (for banner clicks)
        if ($request->has('filter')) {
            switch ($request->filter) {
                case 'gifts':
                    $query->featured();
                    break;
                case 'special_offer':
                    $query->where('is_special_offer', true);
                    break;
                case 'bestseller':
                    $query->bestseller();
                    break;
                case 'new':
                    $query->new();
                    break;
            }
        }

        // Search - word-based matching for better results with multi-word queries
        if ($request->has('search') && !empty($request->search)) {
            $search = trim($request->search);
            $words = array_filter(
                preg_split('/\s+/', $search),
                fn($word) => mb_strlen($word) >= 2
            );
            
            if (!empty($words)) {
                $query->where(function ($q) use ($words) {
                    foreach ($words as $word) {
                        $searchTerm = "%{$word}%";
                        $q->orWhere(function ($subQ) use ($searchTerm) {
                            $subQ->where('name_en', 'like', $searchTerm)
                                ->orWhere('name_ar', 'like', $searchTerm)
                                ->orWhere('name_he', 'like', $searchTerm)
                                ->orWhere('description_en', 'like', $searchTerm)
                                ->orWhere('description_ar', 'like', $searchTerm)
                                ->orWhere('description_he', 'like', $searchTerm)
                                ->orWhere('short_description_en', 'like', $searchTerm)
                                ->orWhere('short_description_ar', 'like', $searchTerm)
                                ->orWhere('short_description_he', 'like', $searchTerm)
                                ->orWhere('search_keywords', 'like', $searchTerm)
                                ->orWhere('sku', 'like', $searchTerm);
                        });
                    }
                });
            }
        }

        // Sort — whitelist columns to prevent SQL injection
        $allowedSorts = ['created_at', 'price', 'sale_price', 'name_en', 'name_ar', 'name_he', 'sales_count', 'views_count', 'stock_quantity'];
        $sortBy = in_array($request->get('sort'), $allowedSorts, true) ? $request->get('sort') : 'created_at';
        $sortOrder = $request->get('order') === 'asc' ? 'asc' : 'desc';
        $query->orderBy($sortBy, $sortOrder);

        $perPage = in_array((int) $request->get('per_page'), [12, 24, 36, 48]) ? (int) $request->get('per_page') : 12;
        $products = $query->paginate($perPage);

        // Preserve filter parameters in pagination links
        $products->appends($request->except('page'));

        // Get cart product IDs for current user/session
        $cartProductIds = $this->getCartProductIds();

        // Get available filters with counts from service
        $availableFilters = $this->filterService->getAvailableFilters();

        // Get active tags for filter carousel
        $tags = collect($availableFilters['tags'] ?? [])->map(function ($tag) {
            return (object) $tag;
        });
        
        // Get current active tag if filtering by tag
        $activeTag = null;
        if ($request->has('tag') && !empty($request->tag)) {
            $activeTag = \App\Models\Tag::where('slug', $request->tag)->first();
        }

        return view('products', compact('products', 'cartProductIds', 'availableFilters', 'tags', 'activeTag'));
    }

    public function show(Product $product)
    {
        $product->load([
            'category' => function($query) {
                $query->with(['specTemplate' => function($q) {
                    $q->with('activeFields');
                }]);
            },
            'brand',
            'images',
            'reviews.user',
            'specValues.field'
        ]);

        // Get related products (same category, different product)
        $relatedProducts = Product::with(['category', 'brand', 'images'])
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->active()
            ->inRandomOrder()
            ->limit(4)
            ->get();


        return view('product-detail', compact('product', 'relatedProducts'));
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
}
