<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Traits\ApiResponses;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    use ApiResponses;

    /**
     * Get dashboard statistics
     */
    public function stats()
    {
        try {
            $stats = [
                'products' => [
                    'total' => \App\Models\Product::count(),
                    'active' => \App\Models\Product::where('is_active', true)->count(),
                    'featured' => \App\Models\Product::where('is_featured', true)->count(),
                    'out_of_stock' => \App\Models\Product::where('stock_status', 'out_of_stock')->count(),
                    'low_stock' => \App\Models\Product::where('stock_quantity', '>', 0)
                        ->where('stock_quantity', '<=', 10)->count(),
                ],
                'categories' => [
                    'total' => \App\Models\Category::count(),
                    'active' => \App\Models\Category::where('is_active', true)->count(),
                ],
                'brands' => [
                    'total' => \App\Models\Brand::count(),
                    'active' => \App\Models\Brand::where('is_active', true)->count(),
                    'featured' => \App\Models\Brand::where('is_featured', true)->count(),
                ],
                'reviews' => [
                    'total' => \App\Models\Review::count(),
                    'approved' => \App\Models\Review::where('is_approved', true)->count(),
                    'pending' => \App\Models\Review::where('is_approved', false)->count(),
                    'average_rating' => round(\App\Models\Review::avg('rating'), 2),
                ],
                'recent_products' => \App\Models\Product::with(['category', 'brand'])
                    ->latest()
                    ->take(5)
                    ->get(),
                'low_stock_products' => \App\Models\Product::with(['category', 'brand'])
                    ->where('stock_quantity', '>', 0)
                    ->where('stock_quantity', '<=', 10)
                    ->orderBy('stock_quantity')
                    ->take(5)
                    ->get(),
            ];

            return $this->successResponse($stats, 'Dashboard statistics retrieved successfully');
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve dashboard statistics', 500);
        }
    }
}
