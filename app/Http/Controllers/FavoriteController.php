<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Favorite;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class FavoriteController extends Controller
{
    /**
     * Display the user's favorite products.
     */
    public function index()
    {
        // Get favorites from session for guests
        $sessionFavorites = Session::get('favorites', []);
        
        if (Auth::check()) {
            // Get authenticated user's favorites from database
            $favorites = Auth::user()->favoriteProducts()
                ->with(['category', 'brand', 'images'])
                ->where('is_active', true)
                ->get();
        } else {
            // Get favorites for guest users from session
            $favorites = Product::whereIn('id', $sessionFavorites)
                ->with(['category', 'brand', 'images'])
                ->where('is_active', true)
                ->get();
        }
        
        return view('favorites', compact('favorites'));
    }

    /**
     * Toggle a product in favorites (add or remove).
     */
    public function toggle(Request $request, $productId)
    {
        $product = Product::findOrFail($productId);
        
        // Ensure productId is an integer
        $productId = (int) $productId;
        
        if (Auth::check()) {
            // For authenticated users, toggle in database
            $favorite = Favorite::where('user_id', Auth::id())
                ->where('product_id', $productId)
                ->first();
            
            if ($favorite) {
                $favorite->delete();
                return response()->json([
                    'success' => true,
                    'action' => 'removed',
                    'message' => 'Product removed from favorites'
                ]);
            } else {
                Favorite::create([
                    'user_id' => Auth::id(),
                    'product_id' => $productId,
                ]);
                return response()->json([
                    'success' => true,
                    'action' => 'added',
                    'message' => 'Product added to favorites'
                ]);
            }
        } else {
            // For guest users, toggle in session
            $sessionFavorites = Session::get('favorites', []);
            
            // Ensure all existing IDs are integers
            $sessionFavorites = array_map('intval', $sessionFavorites);
            
            // Check if product is in favorites (strict comparison with integers)
            if (in_array($productId, $sessionFavorites, true)) {
                // Remove from favorites
                $sessionFavorites = array_filter($sessionFavorites, function($id) use ($productId) {
                    return $id !== $productId;
                });
                $sessionFavorites = array_values($sessionFavorites); // Re-index array
                Session::put('favorites', $sessionFavorites);
                
                return response()->json([
                    'success' => true,
                    'action' => 'removed',
                    'message' => 'Product removed from favorites'
                ]);
            } else {
                // Add to favorites
                $sessionFavorites[] = $productId;
                Session::put('favorites', $sessionFavorites);
                
                return response()->json([
                    'success' => true,
                    'action' => 'added',
                    'message' => 'Product added to favorites'
                ]);
            }
        }
    }

    /**
     * Check if a product is in favorites.
     */
    public function check($productId)
    {
        if (Auth::check()) {
            $isFavorite = Favorite::where('user_id', Auth::id())
                ->where('product_id', $productId)
                ->exists();
        } else {
            $sessionFavorites = Session::get('favorites', []);
            $isFavorite = in_array($productId, $sessionFavorites);
        }
        
        return response()->json([
            'isFavorite' => $isFavorite
        ]);
    }

    /**
     * Get all favorite product IDs for the current user.
     */
    public function getFavoriteIds()
    {
        if (Auth::check()) {
            $favoriteIds = Favorite::where('user_id', Auth::id())
                ->pluck('product_id')
                ->map(fn($id) => (int) $id)  // Ensure integers
                ->toArray();
        } else {
            $favoriteIds = Session::get('favorites', []);
            // Ensure all IDs are integers, not strings
            $favoriteIds = array_map('intval', $favoriteIds);
            // Update session with cleaned integer values
            Session::put('favorites', $favoriteIds);
        }
        
        return response()->json([
            'favoriteIds' => $favoriteIds
        ]);
    }
}
