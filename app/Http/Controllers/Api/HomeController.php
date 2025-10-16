<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BrandResource;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\OfferResource;
use App\Http\Resources\ProductResource;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Offer;
use App\Models\Product;
use App\Traits\ApiResponses;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    use ApiResponses;
    /**
     * Get home page data
     */
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

        return response()->json([
            'success' => true,
            'data' => [
                'featured_products' => ProductResource::collection($featuredProducts),
                'new_products' => ProductResource::collection($newProducts),
                'bestseller_products' => ProductResource::collection($bestsellerProducts),
                'on_sale_products' => ProductResource::collection($onSaleProducts),
                'categories' => CategoryResource::collection($categories),
                'featured_brands' => BrandResource::collection($featuredBrands),
                'active_offers' => OfferResource::collection($activeOffers),
            ]
        ]);
    }

    /**
     * Get specific section data
     */
    public function section($section)
    {
        switch ($section) {
            case 'featured':
                $products = Product::with(['brand', 'category', 'images'])
                    ->active()
                    ->featured()
                    ->limit(12)
                    ->get();
                break;

            case 'new':
                $products = Product::with(['brand', 'category', 'images'])
                    ->active()
                    ->new()
                    ->limit(12)
                    ->get();
                break;

            case 'bestsellers':
                $products = Product::with(['brand', 'category', 'images'])
                    ->active()
                    ->bestseller()
                    ->limit(12)
                    ->get();
                break;

            case 'sale':
                $products = Product::with(['brand', 'category', 'images'])
                    ->active()
                    ->whereNotNull('sale_price')
                    ->where('sale_price', '<', \DB::raw('price'))
                    ->limit(12)
                    ->get();
                break;

            default:
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid section',
                ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'section' => $section,
                'products' => ProductResource::collection($products),
            ]
        ]);
    }
}
