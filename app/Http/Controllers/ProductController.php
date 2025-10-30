<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\CartItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

/**
 * Web Controller - Returns views only
 * All business logic moved to API controllers
 */
class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'brand', 'images'])
            ->active();

        // Filter by multiple categories (support both single and multiple)
        if ($request->has('categories') && !empty($request->categories)) {
            $categories = is_array($request->categories) ? $request->categories : [$request->categories];
            $query->whereHas('category', function ($q) use ($categories) {
                $q->whereIn('slug', $categories);
            });
        }

        // Backward compatibility: single category filter
        if ($request->has('category') && !$request->has('categories')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        // Filter by brand
        if ($request->has('brand')) {
            $query->whereHas('brand', function ($q) use ($request) {
                $q->where('slug', $request->brand);
            });
        }

        // Filter by price range
        if ($request->has('min_price') && $request->min_price !== null && $request->min_price !== '') {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->has('max_price') && $request->max_price !== null && $request->max_price !== '') {
            $query->where('price', '<=', $request->max_price);
        }

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

        // Search
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name_en', 'like', "%{$search}%")
                    ->orWhere('name_ar', 'like', "%{$search}%")
                    ->orWhere('name_he', 'like', "%{$search}%")
                    ->orWhere('description_en', 'like', "%{$search}%")
                    ->orWhere('description_ar', 'like', "%{$search}%")
                    ->orWhere('description_he', 'like', "%{$search}%")
                    ->orWhere('short_description_en', 'like', "%{$search}%")
                    ->orWhere('short_description_ar', 'like', "%{$search}%")
                    ->orWhere('short_description_he', 'like', "%{$search}%")
                    ->orWhere('search_keywords', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        // Sort
        $sortBy = $request->get('sort', 'created_at');
        $sortOrder = $request->get('order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $products = $query->paginate($request->get('per_page', 12));

        // Preserve filter parameters in pagination links
        $products->appends($request->except('page'));

        // Get cart product IDs for current user/session
        $cartProductIds = $this->getCartProductIds();

        // Get all active categories for filter sidebar
        $locale = app()->getLocale();
        $nameColumn = "name_{$locale}";
        $categories = Category::active()
            ->whereNull('parent_id') // Only parent categories
            ->orderBy('order')
            ->get();

        // Get price range for slider
        $priceRange = [
            'min' => Product::active()->min('price') ?? 0,
            'max' => Product::active()->max('price') ?? 10000,
        ];

        return view('products', compact('products', 'cartProductIds', 'categories', 'priceRange'));
    }

    public function show($slug)
    {
        $product = Product::with(['category', 'brand', 'images', 'reviews.user', 'attributes'])
            ->where('slug', $slug)
            ->firstOrFail();
        
        // Get related products (same category, different product)
        $relatedProducts = Product::with(['category', 'brand', 'images'])
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->active()
            ->inRandomOrder()
            ->limit(4)
            ->get();


        return view('product-detail', compact('slug', 'product', 'relatedProducts'));
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
