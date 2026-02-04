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
     * Uses word-based matching for better results with multi-word queries
     */
    private function fetchSuggestions(string $query, int $limit): array
    {
        // Split query into words (filter out very short words)
        $words = array_filter(
            preg_split('/\s+/', trim($query)),
            fn($word) => mb_strlen($word) >= 2
        );
        
        // If no valid words, return empty
        if (empty($words)) {
            return ['products' => [], 'total' => 0];
        }

        // Build query to match ANY word in searchable fields
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
            ->where(function ($q) use ($words) {
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
            })
            ->orderBy('sales_count', 'desc')
            ->orderBy('views_count', 'desc')
            ->limit($limit * 3) // Get more results for relevance sorting
            ->get()
            ->map(function ($product) use ($query, $words) {
                // Determine which language name to display based on search match
                $displayName = $this->getMatchingLanguageName($product, $query);
                
                // Calculate relevance score based on word matches
                $score = $this->calculateRelevanceScore($product, $words);
                
                return [
                    'type' => 'product',
                    'id' => $product->id,
                    'name' => $displayName,
                    'slug' => $product->slug,
                    'image' => $product->main_image,
                    'price' => $product->sale_price ?? $product->price,
                    'original_price' => $product->sale_price ? $product->price : null,
                    'url' => route('product.detail', $product->id),
                    'score' => $score,
                ];
            })
            ->sortByDesc('score') // Sort by relevance score
            ->take($limit) // Limit after sorting
            ->values() // Reset keys
            ->map(function ($item) {
                unset($item['score']); // Remove score from final output
                return $item;
            });

        return [
            'products' => $products->toArray(),
            'total' => $products->count(),
        ];
    }

    /**
     * Get the product name in the language that matches the search query
     * Uses word-based matching to detect which language the user searched in
     * Priority: matched language > current locale > en > ar > he
     */
    private function getMatchingLanguageName($product, string $query): string
    {
        // Split query into words for better matching
        $words = array_filter(
            preg_split('/\s+/', trim($query)),
            fn($word) => mb_strlen($word) >= 2
        );
        
        // Count matches per language
        $scores = ['ar' => 0, 'he' => 0, 'en' => 0];
        
        foreach ($words as $word) {
            if ($product->name_ar && mb_stripos($product->name_ar, $word) !== false) {
                $scores['ar'] += 2;
            }
            if ($product->name_he && mb_stripos($product->name_he, $word) !== false) {
                $scores['he'] += 2;
            }
            if ($product->name_en && mb_stripos($product->name_en, $word) !== false) {
                $scores['en'] += 2;
            }
            if ($product->description_ar && mb_stripos($product->description_ar, $word) !== false) {
                $scores['ar']++;
            }
            if ($product->description_he && mb_stripos($product->description_he, $word) !== false) {
                $scores['he']++;
            }
            if ($product->description_en && mb_stripos($product->description_en, $word) !== false) {
                $scores['en']++;
            }
        }
        
        // Find language with highest score
        arsort($scores);
        $bestLang = array_key_first($scores);
        
        if ($scores[$bestLang] > 0) {
            $name = $product->{"name_{$bestLang}"};
            if ($name) {
                return $name;
            }
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

    /**
     * Calculate relevance score based on how many words match and where they match
     * Higher score = more relevant
     */
    private function calculateRelevanceScore($product, array $words): int
    {
        $score = 0;
        $searchableText = mb_strtolower(implode(' ', array_filter([
            $product->name_en,
            $product->name_ar,
            $product->name_he,
            $product->sku,
            $product->search_keywords,
        ])));
        
        $descriptionText = mb_strtolower(implode(' ', array_filter([
            $product->description_en,
            $product->description_ar,
            $product->description_he,
            $product->short_description_en,
            $product->short_description_ar,
            $product->short_description_he,
        ])));

        foreach ($words as $word) {
            $wordLower = mb_strtolower($word);
            
            // Name/SKU matches are worth more (10 points each)
            if (mb_stripos($searchableText, $wordLower) !== false) {
                $score += 10;
            }
            
            // Description matches worth less (3 points each)
            if (mb_stripos($descriptionText, $wordLower) !== false) {
                $score += 3;
            }
        }
        
        return $score;
    }
}
