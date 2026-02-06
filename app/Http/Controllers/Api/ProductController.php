<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BrandResource;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\ProductResource;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Traits\ApiResponses;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    use ApiResponses;
    /**
     * Get all products with filters
     */
    public function index(Request $request)
    {
        $query = Product::with(['category', 'brand', 'images'])
            ->active();

        // Filter by category
        if ($request->has('category')) {
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
        if ($request->has('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->has('max_price')) {
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

        $products = $query->paginate($request->get('per_page', 12));

        return response()->json([
            'success' => true,
            'data' => [
                'products' => ProductResource::collection($products->items()),
            ],
            'meta' => [
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage(),
                'per_page' => $products->perPage(),
                'total' => $products->total(),
            ]
        ]);
    }

    /**
     * Get a specific product
     */
    public function show(Product $product)
    {
        $product->load(['category', 'brand', 'images', 'reviews.user', 'attributes']);

        // Increment views
        $product->incrementViews();

        // Get related products
        $relatedProducts = Product::with(['category', 'brand'])
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->active()
            ->inStock()
            ->limit(8)
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'product' => new ProductResource($product),
                'related_products' => ProductResource::collection($relatedProducts),
            ]
        ]);
    }

    /**
     * Search products
     */
    public function search(Request $request)
    {
        $request->validate([
            'q' => 'required|string|min:2',
        ]);

        $query = Product::with(['category', 'brand', 'images'])
            ->active();

        $search = $request->q;
        $query->where(function ($q) use ($search) {
            $q->where('name_en', 'like', "%{$search}%")
                ->orWhere('name_ar', 'like', "%{$search}%")
                ->orWhere('description_en', 'like', "%{$search}%")
                ->orWhere('description_ar', 'like', "%{$search}%")
                ->orWhere('sku', 'like', "%{$search}%");
        });

        $products = $query->paginate(12);

        return response()->json([
            'success' => true,
            'data' => [
                'products' => ProductResource::collection($products->items()),
                'query' => $search,
            ],
            'meta' => [
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage(),
                'per_page' => $products->perPage(),
                'total' => $products->total(),
            ]
        ]);
    }

    /**
     * Get featured products
     */
    public function featured()
    {
        $products = Product::with(['category', 'brand', 'images'])
            ->active()
            ->featured()
            ->limit(12)
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'products' => ProductResource::collection($products),
            ]
        ]);
    }
}
