<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    /**
     * Display the cart page
     */
    public function index()
    {
        $identifier = $this->getCartIdentifier();

        $cartItems = CartItem::with('product.images')
            ->where(function($query) use ($identifier) {
                if (isset($identifier['user_id'])) {
                    $query->where('user_id', $identifier['user_id']);
                } else {
                    $query->where('session_id', $identifier['session_id']);
                }
            })
            ->get();

        $total = $cartItems->sum(function($item) {
            return $item->price * $item->quantity;
        });

        return view('cart', compact('cartItems', 'total'));
    }

    /**
     * Get the cart identifier (user_id or session_id)
     */
    private function getCartIdentifier()
    {
        if (Auth::check()) {
            return ['user_id' => Auth::id()];
        } else {
            if (!Session::has('cart_session_id')) {
                Session::put('cart_session_id', Session::getId());
            }
            return ['session_id' => Session::get('cart_session_id')];
        }
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
            // Update quantity
            $cartItem->quantity += $quantity;
            $cartItem->save();
            
            return response()->json([
                'success' => true,
                'action' => 'updated',
                'message' => 'Cart updated',
                'quantity' => $cartItem->quantity
            ]);
        } else {
            // Create new cart item
            $price = $product->sale_price && $product->sale_price < $product->price 
                ? $product->sale_price 
                : $product->price;

            CartItem::create([
                'user_id' => $identifier['user_id'] ?? null,
                'session_id' => $identifier['session_id'] ?? null,
                'product_id' => $productId,
                'quantity' => $quantity,
                'price' => $price
            ]);

            return response()->json([
                'success' => true,
                'action' => 'added',
                'message' => 'Product added to cart'
            ]);
        }
    }

    /**
     * Remove a product from the cart
     */
    public function remove($productId)
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

        if ($cartItem) {
            $cartItem->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Product removed from cart'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Product not in cart'
        ], 404);
    }

    /**
     * Update cart item quantity
     */
    public function updateQuantity(Request $request, $productId)
    {
        $identifier = $this->getCartIdentifier();
        $quantity = $request->input('quantity', 1);

        if ($quantity < 1) {
            return $this->remove($productId);
        }

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
            $cartItem->quantity = $quantity;
            $cartItem->save();
            
            return response()->json([
                'success' => true,
                'message' => 'Quantity updated',
                'quantity' => $cartItem->quantity
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Product not in cart'
        ], 404);
    }

    /**
     * Get cart item count
     */
    public function getCount()
    {
        $identifier = $this->getCartIdentifier();

        $count = CartItem::where(function($query) use ($identifier) {
                if (isset($identifier['user_id'])) {
                    $query->where('user_id', $identifier['user_id']);
                } else {
                    $query->where('session_id', $identifier['session_id']);
                }
            })
            ->sum('quantity');

        return response()->json([
            'count' => $count
        ]);
    }

    /**
     * Get all product IDs in cart
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
            })
            ->pluck('product_id')
            ->toArray();

        return response()->json([
            'productIds' => $productIds
        ]);
    }

    /**
     * Check if product is in cart
     */
    public function check($productId)
    {
        $identifier = $this->getCartIdentifier();

        $inCart = CartItem::where('product_id', $productId)
            ->where(function($query) use ($identifier) {
                if (isset($identifier['user_id'])) {
                    $query->where('user_id', $identifier['user_id']);
                } else {
                    $query->where('session_id', $identifier['session_id']);
                }
            })
            ->exists();

        return response()->json([
            'inCart' => $inCart
        ]);
    }
}
