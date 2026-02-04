<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\CartItem;
use App\Services\CartCacheService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

/**
 * Web Controller - Returns views only
 * All business logic moved to API controllers
 */
class CartController extends Controller
{
    protected $cartCache;

    public function __construct(CartCacheService $cartCache)
    {
        $this->cartCache = $cartCache;
    }
    /**
     * Display the cart page
     */
    public function index()
    {
        $identifier = $this->getCartIdentifier();

        // Fresh query - get latest cart items ordered by creation date (newest first)
        $cartItems = CartItem::with(['product.images', 'product'])
            ->where(function($query) use ($identifier) {
                if (isset($identifier['user_id'])) {
                    $query->where('user_id', $identifier['user_id']);
                } else {
                    $query->where('session_id', $identifier['session_id']);
                }
            })
            ->orderBy('created_at', 'desc') // Show newest items first
            ->get();

        // CRITICAL FIX: Detect and clean up items with deleted products
        $invalidItems = $cartItems->filter(fn($item) => $item->product === null);
        
        if ($invalidItems->isNotEmpty()) {
            CartItem::whereIn('id', $invalidItems->pluck('id'))->delete();
            
            session()->flash('warning', 
                __('messages.cart_items_removed', ['count' => $invalidItems->count()])
            );
        }

        // Filter out invalid items
        $cartItems = $cartItems->filter(fn($item) => $item->product !== null);

        $total = $cartItems->sum(function($item) {
            return $item->price * $item->quantity;
        });

        return view('cart', compact('cartItems', 'total'));
    }

    /**
     * Add a product to the cart
     */
    public function add(Request $request, $productId)
    {
        $product = Product::findOrFail($productId);
        
        // Check if product is in stock
        if ($product->stock_status === 'out_of_stock') {
            return response()->json([
                'success' => false,
                'message' => 'Product is out of stock'
            ], 400);
        }

        // CRITICAL FIX: Calculate max allowed quantity
        $maxQuantity = $product->track_stock 
            ? min($product->stock_quantity, 100) // Max 100 or available stock
            : 100; // Max 100 for non-tracked items

        $request->validate([
            'quantity' => "nullable|integer|min:1|max:{$maxQuantity}",
        ]);

        $identifier = $this->getCartIdentifier();
        $quantity = $request->input('quantity', 1);

        // Check if product already in cart
        $cartItem = CartItem::where('product_id', $productId)
            ->where(function($query) use ($identifier) {
                if (isset($identifier['user_id'])) {
                    $query->where('user_id', $identifier['user_id']);
                } else {
                    $query->where('session_id', $identifier['session_id']);
                }
            })
            ->first();

        if ($cartItem) {
            // CRITICAL FIX: Check total quantity won't exceed stock
            $newQuantity = $cartItem->quantity + $quantity;
            
            if ($product->track_stock && $newQuantity > $product->stock_quantity) {
                return response()->json([
                    'success' => false,
                    'message' => "Only {$product->stock_quantity} items available",
                    'max_quantity' => $product->stock_quantity,
                ], 400);
            }
            
            if ($newQuantity > $maxQuantity) {
                return response()->json([
                    'success' => false,
                    'message' => "Maximum {$maxQuantity} items per order",
                ], 400);
            }

            // Update quantity
            $cartItem->quantity = $newQuantity;
            $cartItem->save();
            
            // Clear cart cache
            $this->cartCache->clearCache($identifier);
            
            return response()->json([
                'success' => true,
                'action' => 'updated',
                'message' => 'Cart updated',
                'cart_count' => $this->getCartCount($identifier),
            ]);
        } else {
            // Create new cart item
            $price = $product->sale_price && $product->sale_price < $product->price 
                ? $product->sale_price 
                : $product->price;

            $cartItem = CartItem::create([
                'user_id' => $identifier['user_id'] ?? null,
                'session_id' => $identifier['session_id'] ?? null,
                'product_id' => $productId,
                'quantity' => $quantity,
                'price' => $price,
            ]);

            // Clear cart cache
            $this->cartCache->clearCache($identifier);

            return response()->json([
                'success' => true,
                'action' => 'added',
                'message' => 'Product added to cart',
                'cart_count' => $this->getCartCount($identifier),
            ]);
        }
    }

    /**
     * Update cart item quantity
     */
    public function update(Request $request, $productId)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $identifier = $this->getCartIdentifier();

        $cartItem = CartItem::where('product_id', $productId)
            ->where(function($query) use ($identifier) {
                if (isset($identifier['user_id'])) {
                    $query->where('user_id', $identifier['user_id']);
                } else {
                    $query->where('session_id', $identifier['session_id']);
                }
            })
            ->firstOrFail();

        $cartItem->quantity = $request->quantity;
        $cartItem->save();

        // Clear cart cache
        $this->cartCache->clearCache($identifier);

        return response()->json([
            'success' => true,
            'message' => 'Cart updated',
            'cart_count' => $this->getCartCount($identifier),
        ]);
    }

    /**
     * Remove a product from the cart
     */
    public function remove($productId)
    {
        $identifier = $this->getCartIdentifier();

        $deleted = CartItem::where('product_id', $productId)
            ->where(function($query) use ($identifier) {
                if (isset($identifier['user_id'])) {
                    $query->where('user_id', $identifier['user_id']);
                } else {
                    $query->where('session_id', $identifier['session_id']);
                }
            })
            ->delete();

        if ($deleted) {
            // Clear cart cache
            $this->cartCache->clearCache($identifier);
            
            return response()->json([
                'success' => true,
                'message' => 'Product removed from cart',
                'cart_count' => $this->getCartCount($identifier),
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Product not found in cart'
        ], 404);
    }

    /**
     * Check if a product is in the cart
     */
    public function check($productId)
    {
        $identifier = $this->getCartIdentifier();

        $cartItem = CartItem::where('product_id', $productId)
            ->where(function($query) use ($identifier) {
                if (isset($identifier['user_id'])) {
                    $query->where('user_id', $identifier['user_id']);
                } else {
                    $query->where('session_id', $identifier['session_id']);
                }
            })
            ->first();

        return response()->json([
            'success' => true,
            'in_cart' => !is_null($cartItem),
            'quantity' => $cartItem ? $cartItem->quantity : 0,
        ]);
    }

    /**
     * Get cart identifier (user_id or session_id)
     */
    private function getCartIdentifier()
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

    /**
     * Get total cart item count
     */
    private function getCartCount($identifier)
    {
        return CartItem::where(function($query) use ($identifier) {
            if (isset($identifier['user_id'])) {
                $query->where('user_id', $identifier['user_id']);
            } else {
                $query->where('session_id', $identifier['session_id']);
            }
        })->sum('quantity');
    }

    /**
     * Get cart count for header badge (public endpoint)
     */
    public function getCount()
    {
        $identifier = $this->getCartIdentifier();
        $count = $this->getCartCount($identifier);

        return response()->json([
            'success' => true,
            'count' => (int) $count,
        ]);
    }

    /**
     * Get cart product IDs for quick checks
     */
    public function getProductIds()
    {
        $identifier = $this->getCartIdentifier();

        $productIds = CartItem::where(function($query) use ($identifier) {
            if (isset($identifier['user_id'])) {
                $query->where('user_id', $identifier['user_id']);
            } else {
                $query->where('session_id', $identifier['session_id']);
            }
        })->pluck('product_id')->toArray();

        return response()->json([
            'success' => true,
            'productIds' => array_map('intval', $productIds),
        ]);
    }

    /**
     * Get cart items with quantities for button state sync
     */
    public function getItems()
    {
        $identifier = $this->getCartIdentifier();

        $items = CartItem::where(function($query) use ($identifier) {
            if (isset($identifier['user_id'])) {
                $query->where('user_id', $identifier['user_id']);
            } else {
                $query->where('session_id', $identifier['session_id']);
            }
        })->select('product_id', 'quantity')->get();

        return response()->json([
            'success' => true,
            'items' => $items->map(function($item) {
                return [
                    'product_id' => (int) $item->product_id,
                    'quantity' => (int) $item->quantity,
                ];
            }),
        ]);
    }
}

