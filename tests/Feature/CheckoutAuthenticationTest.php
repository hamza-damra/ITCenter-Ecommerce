<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use App\Models\CartItem;
use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Session;

class CheckoutAuthenticationTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $product;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create a test user
        $this->user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
        ]);

        // Create a test product
        $this->product = Product::factory()->create([
            'name_en' => 'Test Product',
            'name_ar' => 'منتج تجريبي',
            'name_he' => 'מוצר בדיקה',
            'price' => 100.00,
            'stock_quantity' => 10,
            'stock_status' => 'in_stock',
            'is_active' => true,
        ]);
    }

    /**
     * Test 1: Guest user cannot access checkout page
     */
    public function test_guest_cannot_access_checkout_page()
    {
        // Add product to guest cart
        CartItem::create([
            'session_id' => Session::getId(),
            'product_id' => $this->product->id,
            'quantity' => 1,
            'price' => $this->product->price,
        ]);

        // Try to access checkout as guest
        $response = $this->get(route('checkout.index'));

        // Should redirect to login
        $response->assertRedirect(route('login'));
        
        echo "✅ Test 1 PASSED: Guest redirected to login when accessing checkout\n";
    }

    /**
     * Test 2: Guest user cannot submit checkout form (process order)
     */
    public function test_guest_cannot_process_order()
    {
        // Add product to guest cart
        CartItem::create([
            'session_id' => Session::getId(),
            'product_id' => $this->product->id,
            'quantity' => 1,
            'price' => $this->product->price,
        ]);

        $orderData = [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john@example.com',
            'phone' => '1234567890',
            'address' => '123 Test St',
            'city' => 'Test City',
            'postal_code' => '12345',
            'country' => 'Test Country',
        ];

        // Try to submit checkout form as guest
        $response = $this->post(route('checkout.process'), $orderData);

        // Should redirect to login
        $response->assertRedirect(route('login'));

        // Verify NO order was created in database
        $this->assertDatabaseCount('orders', 0);
        
        echo "✅ Test 2 PASSED: Guest cannot process order - no database insert\n";
    }

    /**
     * Test 3: Authenticated user CAN access checkout page
     */
    public function test_authenticated_user_can_access_checkout()
    {
        // Login as user
        $this->actingAs($this->user);

        // Add product to user's cart
        CartItem::create([
            'user_id' => $this->user->id,
            'product_id' => $this->product->id,
            'quantity' => 1,
            'price' => $this->product->price,
        ]);

        // Access checkout page
        $response = $this->get(route('checkout.index'));

        // Should show checkout page (200 OK)
        $response->assertOk();
        $response->assertViewIs('checkout');
        
        echo "✅ Test 3 PASSED: Authenticated user can access checkout page\n";
    }

    /**
     * Test 4: Authenticated user CAN successfully place order
     */
    public function test_authenticated_user_can_place_order()
    {
        // Login as user
        $this->actingAs($this->user);

        // Add product to user's cart
        CartItem::create([
            'user_id' => $this->user->id,
            'product_id' => $this->product->id,
            'quantity' => 2,
            'price' => $this->product->price,
        ]);

        $orderData = [
            'first_name' => 'Jane',
            'last_name' => 'Smith',
            'email' => 'jane@example.com',
            'phone' => '9876543210',
            'address' => '456 Main Ave',
            'city' => 'Demo City',
            'postal_code' => '67890',
            'country' => 'Demo Country',
            'notes' => 'Please deliver quickly',
        ];

        // Submit checkout form
        $response = $this->post(route('checkout.process'), $orderData);

        // Should create order and redirect
        $response->assertRedirect();

        // Verify order was created with correct user_id
        $this->assertDatabaseHas('orders', [
            'user_id' => $this->user->id,
            'customer_email' => 'jane@example.com',
            'status' => 'pending',
        ]);

        // Verify cart was cleared
        $this->assertDatabaseMissing('cart_items', [
            'user_id' => $this->user->id,
        ]);

        $order = Order::where('user_id', $this->user->id)->first();
        $this->assertNotNull($order);
        $this->assertEquals($this->user->id, $order->user_id);
        
        echo "✅ Test 4 PASSED: Authenticated user successfully placed order\n";
        echo "   - Order ID: {$order->id}\n";
        echo "   - Order Number: {$order->order_number}\n";
        echo "   - User ID: {$order->user_id}\n";
    }

    /**
     * Test 5: Database constraint - user_id cannot be NULL
     */
    public function test_database_rejects_null_user_id()
    {
        $this->expectException(\Exception::class);

        // Try to create order with null user_id
        Order::create([
            'order_number' => 'TEST-' . time(),
            'user_id' => null, // This should fail
            'customer_name' => 'Test Customer',
            'customer_email' => 'test@test.com',
            'customer_phone' => '1234567890',
            'shipping_address' => 'Test Address',
            'shipping_city' => 'Test City',
            'shipping_postal_code' => '12345',
            'shipping_country' => 'Test Country',
            'subtotal' => 100,
            'tax' => 17,
            'shipping_cost' => 25,
            'total' => 142,
            'status' => 'pending',
            'payment_status' => 'pending',
            'payment_method' => 'cash_on_delivery',
        ]);

        // If we reach here, test failed
        $this->fail('Database should reject NULL user_id');
    }

    /**
     * Test 6: Verify session_id column no longer exists
     */
    public function test_session_id_column_removed()
    {
        // Check if session_id column exists in orders table
        $columns = \Schema::getColumnListing('orders');
        
        $this->assertNotContains('session_id', $columns);
        
        echo "✅ Test 6 PASSED: session_id column successfully removed from orders table\n";
    }

    /**
     * Test 7: No ghost orders exist in database
     */
    public function test_no_ghost_orders_exist()
    {
        // Check for any orders with null user_id
        $ghostOrders = Order::whereNull('user_id')->count();
        
        $this->assertEquals(0, $ghostOrders);
        
        echo "✅ Test 7 PASSED: No ghost orders (user_id = NULL) found in database\n";
    }

    /**
     * Test 8: Checkout with empty cart redirects
     */
    public function test_checkout_with_empty_cart_redirects()
    {
        // Login as user
        $this->actingAs($this->user);

        // Try to checkout with empty cart
        $response = $this->get(route('checkout.index'));

        // Should redirect to cart page
        $response->assertRedirect(route('cart.index'));
        
        echo "✅ Test 8 PASSED: Empty cart redirects to cart page\n";
    }
}
