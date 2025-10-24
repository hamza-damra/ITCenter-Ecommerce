<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

/**
 * Web Controller - Returns views only
 */
class CheckoutController extends Controller
{
    /**
     * Display the checkout page
     * IMPORTANT: Only accessible to authenticated users
     */
    public function index()
    {
        // Extra defensive check (middleware already protects this route)
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('info', __('messages.please_login_to_checkout'));
        }

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
     * CRITICAL: Only authenticated users can create orders
     */
    public function processOrder(Request $request)
    {
        // Defensive authentication check - MUST be logged in to create an order
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', __('messages.must_login_to_place_order'));
        }

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

        // Get authenticated user's ID
        $userId = Auth::id();

        // Get cart items for authenticated user only
        $cartItems = CartItem::with('product')
            ->where('user_id', $userId)
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('error', __('messages.cart_empty'));
        }

        // Calculate totals
        $subtotal = $cartItems->sum(function($item) {
            return $item->price * $item->quantity;
        });
        
        $taxRate = 0.17; // 17% VAT
        $tax = $subtotal * $taxRate;
        $shippingCost = $subtotal >= 200 ? 0 : 25; // Free shipping over $200
        $total = $subtotal + $tax + $shippingCost;

        DB::beginTransaction();
        try {
            // Create the order - ONLY for authenticated users
            $order = Order::create([
                'order_number' => Order::generateOrderNumber(),
                'user_id' => $userId, // REQUIRED: Must have valid user_id
                'customer_name' => $validated['first_name'] . ' ' . $validated['last_name'],
                'customer_email' => $validated['email'],
                'customer_phone' => $validated['phone'],
                'shipping_address' => $validated['address'],
                'shipping_city' => $validated['city'],
                'shipping_state' => $validated['state'] ?? null,
                'shipping_postal_code' => $validated['postal_code'],
                'shipping_country' => $validated['country'],
                'subtotal' => $subtotal,
                'tax' => $tax,
                'shipping_cost' => $shippingCost,
                'discount' => 0,
                'total' => $total,
                'status' => 'pending',
                'payment_status' => 'pending',
                'payment_method' => 'cash_on_delivery',
                'notes' => $validated['notes'] ?? null,
            ]);

            // Create order items
            foreach ($cartItems as $cartItem) {
                $product = $cartItem->product;
                
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'product_name_en' => $product->name_en,
                    'product_name_ar' => $product->name_ar,
                    'product_name_he' => $product->name_he ?? $product->name_en,
                    'product_slug' => $product->slug,
                    'product_image' => $product->main_image,
                    'product_sku' => $product->sku,
                    'price' => $cartItem->price,
                    'original_price' => $product->original_price,
                    'quantity' => $cartItem->quantity,
                    'subtotal' => $cartItem->price * $cartItem->quantity,
                ]);

                // Update product stock
                if ($product->stock_quantity > 0) {
                    $product->decrement('stock_quantity', $cartItem->quantity);
                }
            }

            // Clear the cart for authenticated user
            CartItem::where('user_id', $userId)->delete();

            DB::commit();

            return redirect()->route('orders.show', $order->order_number)
                ->with('success', __('messages.order_placed_successfully'));
                
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', __('messages.order_error'))
                ->withInput();
        }
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
