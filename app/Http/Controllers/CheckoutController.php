<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

/**
 * Web Controller - Returns views only
 */
class CheckoutController extends Controller
{
    /**
     * Display the checkout page
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

        // Redirect to cart if empty
        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('error', __('messages.cart_empty'));
        }

        $subtotal = $cartItems->sum(function($item) {
            return $item->price * $item->quantity;
        });

        // Calculate tax and shipping (can be customized)
        $taxRate = 0.17; // 17% VAT (Israel standard)
        $tax = $subtotal * $taxRate;
        $shippingFee = $subtotal >= 200 ? 0 : 25; // Free shipping over $200
        $total = $subtotal + $tax + $shippingFee;

        $user = Auth::user();

        return view('checkout', compact('cartItems', 'subtotal', 'tax', 'shippingFee', 'total', 'user'));
    }

    /**
     * Process the order
     */
    public function processOrder(Request $request)
    {
        // Validate the request
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:500',
            'city' => 'required|string|max:255',
            'state' => 'nullable|string|max:255',
            'postal_code' => 'required|string|max:20',
            'country' => 'required|string|max:255',
            'notes' => 'nullable|string|max:1000',
        ]);

        $identifier = $this->getCartIdentifier();

        $cartItems = CartItem::with('product')
            ->where(function($query) use ($identifier) {
                if (isset($identifier['user_id'])) {
                    $query->where('user_id', $identifier['user_id']);
                } else {
                    $query->where('session_id', $identifier['session_id']);
                }
            })
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('error', __('messages.cart_empty'));
        }

        // Here you would create an order in the database
        // For now, we'll just clear the cart and show success message
        
        // Clear the cart
        CartItem::where(function($query) use ($identifier) {
            if (isset($identifier['user_id'])) {
                $query->where('user_id', $identifier['user_id']);
            } else {
                $query->where('session_id', $identifier['session_id']);
            }
        })->delete();

        return redirect()->route('home')
            ->with('success', __('messages.order_placed_successfully'));
    }

    /**
     * Get cart identifier (user_id or session_id)
     */
    private function getCartIdentifier()
    {
        if (Auth::check()) {
            return ['user_id' => Auth::id()];
        }

        $sessionId = Session::getId();
        if (!$sessionId) {
            Session::start();
            $sessionId = Session::getId();
        }

        return ['session_id' => $sessionId];
    }
}
