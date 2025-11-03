<?php

namespace App\Services;

use App\Models\CartItem;
use Illuminate\Support\Facades\Cache;

class CartCacheService
{
    private const CACHE_TTL = 3600; // 1 hour
    
    /**
     * Get cached cart product IDs
     */
    public static function getProductIds($identifier): array
    {
        $cacheKey = self::getCacheKey($identifier);
        
        return Cache::remember($cacheKey, self::CACHE_TTL, function () use ($identifier) {
            return CartItem::where(function($query) use ($identifier) {
                if (isset($identifier['user_id'])) {
                    $query->where('user_id', $identifier['user_id']);
                } else {
                    $query->where('session_id', $identifier['session_id']);
                }
            })->pluck('product_id')->toArray();
        });
    }
    
    /**
     * Get cached cart count
     */
    public static function getCount($identifier): int
    {
        $cacheKey = self::getCacheKey($identifier) . '_count';
        
        return Cache::remember($cacheKey, self::CACHE_TTL, function () use ($identifier) {
            return CartItem::where(function($query) use ($identifier) {
                if (isset($identifier['user_id'])) {
                    $query->where('user_id', $identifier['user_id']);
                } else {
                    $query->where('session_id', $identifier['session_id']);
                }
            })->count();
        });
    }
    
    /**
     * Get cached cart items with products
     */
    public static function getItems($identifier)
    {
        $cacheKey = self::getCacheKey($identifier) . '_items';
        
        return Cache::remember($cacheKey, self::CACHE_TTL, function () use ($identifier) {
            return CartItem::with('product.images')
                ->where(function($query) use ($identifier) {
                    if (isset($identifier['user_id'])) {
                        $query->where('user_id', $identifier['user_id']);
                    } else {
                        $query->where('session_id', $identifier['session_id']);
                    }
                })
                ->get()
                ->filter(function($item) {
                    // Remove cart items with missing/deleted products
                    return $item->product !== null;
                });
        });
    }
    
    /**
     * Clear cart cache when cart is modified
     */
    public static function clearCache($identifier): void
    {
        $cacheKey = self::getCacheKey($identifier);
        Cache::forget($cacheKey);
        Cache::forget($cacheKey . '_count');
        Cache::forget($cacheKey . '_items');
    }
    
    /**
     * Clear cart cache for multiple identifiers (e.g., after user login)
     */
    public static function clearMultiple(array $identifiers): void
    {
        foreach ($identifiers as $identifier) {
            self::clearCache($identifier);
        }
    }
    
    /**
     * Generate cache key
     */
    private static function getCacheKey($identifier): string
    {
        if (isset($identifier['user_id'])) {
            return "cart_user_{$identifier['user_id']}";
        }
        return "cart_session_{$identifier['session_id']}";
    }
}
