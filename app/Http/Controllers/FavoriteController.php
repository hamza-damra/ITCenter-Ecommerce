<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Favorite;
use App\Models\Product;
use App\Models\CartItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

/**
 * Web Controller - Returns views only
 * All business logic moved to API controllers
 */
class FavoriteController extends Controller
{
    /**
     * Display the user's favorite products.
     */
    public function index()
    {
        $identifier = $this->getIdentifier();

        $favorites = Favorite::with('product.images')
            ->where(function($query) use ($identifier) {
                if (isset($identifier['user_id'])) {
                    $query->where('user_id', $identifier['user_id']);
                } else {
                    $query->where('session_id', $identifier['session_id']);
                }
            })
            ->get()
            ->pluck('product') // Extract just the products
            ->filter(); // Remove any null products

        // Get cart product IDs for current user/session
        $cartProductIds = $this->getCartProductIds();

        return view('favorites', compact('favorites', 'cartProductIds'));
    }

    /**
     * Get cart product IDs for current user/session
     */
    private function getCartProductIds()
    {
        $identifier = $this->getIdentifier();

        return CartItem::where(function($query) use ($identifier) {
            if (isset($identifier['user_id'])) {
                $query->where('user_id', $identifier['user_id']);
            } else {
                $query->where('session_id', $identifier['session_id']);
            }
        })->pluck('product_id')->toArray();
    }

    /**
     * Get favorites count for header badge (public endpoint)
     */
    public function getCount()
    {
        $identifier = $this->getIdentifier();

        $count = Favorite::where(function($query) use ($identifier) {
            if (isset($identifier['user_id'])) {
                $query->where('user_id', $identifier['user_id']);
            } else {
                $query->where('session_id', $identifier['session_id']);
            }
        })->count();

        return response()->json([
            'success' => true,
            'count' => (int) $count,
        ]);
    }

    /**
     * Get favorite product IDs for header badge
     */
    public function getIds()
    {
        $identifier = $this->getIdentifier();

        $favoriteIds = Favorite::where(function($query) use ($identifier) {
            if (isset($identifier['user_id'])) {
                $query->where('user_id', $identifier['user_id']);
            } else {
                $query->where('session_id', $identifier['session_id']);
            }
        })->pluck('product_id')->toArray();

        return response()->json([
            'success' => true,
            'favoriteIds' => array_map('intval', $favoriteIds),
        ]);
    }

    /**
     * Toggle a product in favorites
     */
    public function toggle($productId)
    {
        $product = Product::findOrFail($productId);
        $identifier = $this->getIdentifier();

        // Check if product is already in favorites
        $favorite = Favorite::where('product_id', $productId)
            ->where(function($query) use ($identifier) {
                if (isset($identifier['user_id'])) {
                    $query->where('user_id', $identifier['user_id']);
                } else {
                    $query->where('session_id', $identifier['session_id']);
                }
            })
            ->first();

        if ($favorite) {
            // Remove from favorites
            $favorite->delete();
            
            return response()->json([
                'success' => true,
                'action' => 'removed',
                'message' => 'Removed from favorites',
                'in_favorites' => false,
            ]);
        } else {
            // Add to favorites
            Favorite::create([
                'user_id' => $identifier['user_id'] ?? null,
                'session_id' => $identifier['session_id'] ?? null,
                'product_id' => $productId,
            ]);

            return response()->json([
                'success' => true,
                'action' => 'added',
                'message' => 'Added to favorites',
                'in_favorites' => true,
            ]);
        }
    }

    /**
     * Get identifier (user_id or session_id)
     */
    private function getIdentifier()
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

