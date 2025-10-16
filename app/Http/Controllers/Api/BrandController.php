<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BrandResource;
use App\Http\Resources\ProductResource;
use App\Models\Brand;
use App\Models\Product;
use App\Traits\ApiResponses;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    use ApiResponses;
    /**
     * Get all brands with optional featured brands
     */
    public function index()
    {
        $brands = Brand::active()
            ->withCount('products')
            ->orderBy('order')
            ->get();

        $featuredBrands = $brands->where('is_featured', true)->values();

        return response()->json([
            'success' => true,
            'data' => [
                'brands' => BrandResource::collection($brands),
                'featured_brands' => BrandResource::collection($featuredBrands),
            ]
        ]);
    }

    /**
     * Get a specific brand with its products
     */
    public function show($slug)
    {
        $brand = Brand::where('slug', $slug)
            ->active()
            ->withCount('products')
            ->firstOrFail();

        $products = Product::with(['category', 'images'])
            ->where('brand_id', $brand->id)
            ->active()
            ->paginate(12);

        return response()->json([
            'success' => true,
            'data' => [
                'brand' => new BrandResource($brand),
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
