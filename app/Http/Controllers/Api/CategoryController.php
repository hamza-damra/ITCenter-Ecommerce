<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\ProductResource;
use App\Models\Category;
use App\Models\Product;
use App\Traits\ApiResponses;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    use ApiResponses;
    /**
     * Get all categories with optional children
     */
    public function index()
    {
        $categories = Category::with(['children'])
            ->active()
            ->parent()
            ->withCount('products')
            ->orderBy('order')
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'categories' => CategoryResource::collection($categories),
            ]
        ]);
    }

    /**
     * Get a specific category with its products
     */
    public function show($slug)
    {
        $category = Category::with(['children'])
            ->where('slug', $slug)
            ->active()
            ->withCount('products')
            ->firstOrFail();

        $products = Product::with(['brand', 'images'])
            ->where('category_id', $category->id)
            ->active()
            ->paginate(12);

        return response()->json([
            'success' => true,
            'data' => [
                'category' => new CategoryResource($category),
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
}
