<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SearchSuggestionController extends Controller
{
    /**
     * Get search suggestions - products only
     * Returns product name in the language that matches the search query
     */
    public function suggestions(Request $request)
    {
        $request->validate([
            'q' => 'required|string|min:1|max:100',
        ]);

        $query = trim($request->q);
        $limit = min((int) $request->get('limit', 10), 15);

        // Cache for 2 minutes
        $cacheKey = "search_suggestions:" . md5(strtolower($query));
        
        $suggestions = Cache::remember($cacheKey, 120, function () use ($query, $limit) {
            return $this->fetchSuggestions($query, $limit);
        });

        return response()->json([
            'success' => true,
            'data' => $suggestions,
            'query' => $query,
        ]);
    }

    /**
     * Fetch product suggestions - detect matching language for display
     */
    private function fetchSuggestions(string $query, int $limit): array
    {
        $searchTerm = "%{$query}%";

        // Get products matching the search
        $products = Product::query()
            ->select([
                'id',
                'slug',
                'name_en',
                'name_ar',
                'name_he',
                'description_en',
                'description_ar',
                'description_he',
                'short_description_en',
                'short_description_ar',
                'short_description_he',
                'main_image',
                'price',
                'sale_price',
                'search_keywords',
                'sku',
            ])
            ->where('is_active', true)
            ->where(function ($q) use ($searchTerm) {
                $q->where('name_en', 'like', $searchTerm)
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
            })
            ->orderBy('sales_count', 'desc')
            ->orderBy('views_count', 'desc')
            ->limit($limit)
            ->get()
            ->map(function ($product) use ($query) {
                // Determine which language name to display based on search match
                $displayName = $this->getMatchingLanguageName($product, $query);
                
                return [
                    'type' => 'product',
                    'id' => $product->id,
                    'name' => $displayName,
                    'slug' => $product->slug,
                    'image' => $product->main_image,
                    'price' => $product->sale_price ?? $product->price,
                    'original_price' => $product->sale_price ? $product->price : null,
                    'url' => route('product.detail', $product->slug),
                ];
            });

        return [
            'products' => $products->toArray(),
            'total' => $products->count(),
        ];
    }

    /**
     * Get the product name in the language that matches the search query
     * Priority: matched language > ar > en > he
     */
    private function getMatchingLanguageName($product, string $query): string
    {
        $queryLower = mb_strtolower($query);
        
        // Check which language field contains the search query
        // Check Arabic first (RTL language)
        if ($product->name_ar && mb_stripos($product->name_ar, $query) !== false) {
            return $product->name_ar;
        }
        
        // Check Hebrew
        if ($product->name_he && mb_stripos($product->name_he, $query) !== false) {
            return $product->name_he;
        }
        
        // Check English
        if ($product->name_en && mb_stripos($product->name_en, $query) !== false) {
            return $product->name_en;
        }
        
        // Check descriptions for language match
        if ($product->description_ar && mb_stripos($product->description_ar, $query) !== false) {
            return $product->name_ar ?: $product->name_en ?: $product->name_he ?: 'Unknown';
        }
        
        if ($product->description_he && mb_stripos($product->description_he, $query) !== false) {
            return $product->name_he ?: $product->name_en ?: $product->name_ar ?: 'Unknown';
        }
        
        if ($product->description_en && mb_stripos($product->description_en, $query) !== false) {
            return $product->name_en ?: $product->name_ar ?: $product->name_he ?: 'Unknown';
        }
        
        // Check short descriptions
        if ($product->short_description_ar && mb_stripos($product->short_description_ar, $query) !== false) {
            return $product->name_ar ?: $product->name_en ?: $product->name_he ?: 'Unknown';
        }
        
        if ($product->short_description_he && mb_stripos($product->short_description_he, $query) !== false) {
            return $product->name_he ?: $product->name_en ?: $product->name_ar ?: 'Unknown';
        }
        
        if ($product->short_description_en && mb_stripos($product->short_description_en, $query) !== false) {
            return $product->name_en ?: $product->name_ar ?: $product->name_he ?: 'Unknown';
        }
        
        // Default fallback: use current locale or first available
        $locale = app()->getLocale();
        $localeName = $product->{"name_{$locale}"};
        
        if ($localeName) {
            return $localeName;
        }
        
        // Final fallback
        return $product->name_en ?: $product->name_ar ?: $product->name_he ?: 'Unknown';
    }
}
