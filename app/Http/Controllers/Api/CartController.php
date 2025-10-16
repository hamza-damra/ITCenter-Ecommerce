<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CartItemResource;
use App\Models\CartItem;
use App\Models\Product;
use App\Traits\ApiResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    use ApiResponses;
    /**
     * Get cart items
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

        return response()->json([
            'success' => true,
            'data' => [
                'items' => CartItemResource::collection($cartItems),
                'total' => (float) $total,
                'count' => $cartItems->count(),
                'items_count' => $cartItems->sum('quantity'),
            ]
        ]);
    }

    /**
     * Add a product to the cart
     */
    public function add(Request $request, $productId)
    {
        $request->validate([
            'quantity' => 'nullable|integer|min:1',
        ]);

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
                'data' => new CartItemResource($cartItem->load('product')),
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
                'price' => $price
            ]);

            return response()->json([
                'success' => true,
                'action' => 'added',
                'message' => 'Product added to cart',
                'data' => new CartItemResource($cartItem->load('product')),
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
            ->first();

        if (!$cartItem) {
            return response()->json([
                'success' => false,
                'message' => 'Product not in cart'
            ], 404);
        }

        $cartItem->quantity = $request->quantity;
        $cartItem->save();

        return response()->json([
            'success' => true,
            'message' => 'Cart updated',
            'data' => new CartItemResource($cartItem->load('product')),
        ]);
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
     * Check if a product is in cart
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
            'success' => true,
            'in_cart' => $inCart
        ]);
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
}
