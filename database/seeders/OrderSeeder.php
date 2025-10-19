<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        
        // Get users and products
        $users = User::where('role', 'customer')->get();
        $products = Product::active()->get();

        if ($users->isEmpty() || $products->isEmpty()) {
            $this->command->warn('Please seed users and products first!');
            return;
        }

        $statuses = ['pending', 'processing', 'shipped', 'delivered', 'cancelled'];
        $paymentStatuses = ['pending', 'paid', 'failed'];

        $this->command->info('Creating sample orders...');

        // Create 30 sample orders
        for ($i = 0; $i < 30; $i++) {
            $user = $users->random();
            $status = $faker->randomElement($statuses);
            $paymentStatus = $status === 'delivered' ? 'paid' : $faker->randomElement($paymentStatuses);
            
            // Calculate dates
            $createdAt = $faker->dateTimeBetween('-3 months', 'now');
            $shippedAt = null;
            $deliveredAt = null;
            $cancelledAt = null;
            $paidAt = null;

            if ($paymentStatus === 'paid') {
                $paidAt = $faker->dateTimeBetween($createdAt, '+1 day');
            }

            if ($status === 'shipped' || $status === 'delivered') {
                $shippedAt = $faker->dateTimeBetween($createdAt, '+2 days');
            }

            if ($status === 'delivered') {
                $deliveredAt = $faker->dateTimeBetween($shippedAt, '+5 days');
            }

            if ($status === 'cancelled') {
                $cancelledAt = $faker->dateTimeBetween($createdAt, '+1 day');
            }

            // Get random products for this order
            $orderProducts = $products->random(rand(1, 5));
            $subtotal = 0;

            $order = Order::create([
                'order_number' => Order::generateOrderNumber(),
                'user_id' => $user->id,
                'session_id' => null,
                'customer_name' => $user->first_name . ' ' . $user->last_name,
                'customer_email' => $user->email,
                'customer_phone' => $user->phone ?? $faker->phoneNumber,
                'shipping_address' => $faker->streetAddress,
                'shipping_city' => $faker->city,
                'shipping_state' => $faker->state,
                'shipping_postal_code' => $faker->postcode,
                'shipping_country' => $faker->country,
                'subtotal' => 0, // Will update after items
                'tax' => 0,
                'shipping_cost' => 0,
                'discount' => 0,
                'total' => 0,
                'status' => $status,
                'payment_status' => $paymentStatus,
                'payment_method' => 'cash_on_delivery',
                'notes' => $faker->boolean(30) ? $faker->sentence : null,
                'paid_at' => $paidAt,
                'shipped_at' => $shippedAt,
                'delivered_at' => $deliveredAt,
                'cancelled_at' => $cancelledAt,
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);

            // Create order items
            foreach ($orderProducts as $product) {
                $quantity = rand(1, 3);
                $price = $product->sale_price ?? $product->price;
                $itemSubtotal = $price * $quantity;
                $subtotal += $itemSubtotal;

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'product_name_en' => $product->name_en,
                    'product_name_ar' => $product->name_ar,
                    'product_name_he' => $product->name_he ?? $product->name_en,
                    'product_slug' => $product->slug,
                    'product_image' => $product->main_image,
                    'product_sku' => $product->sku,
                    'price' => $price,
                    'original_price' => $product->price,
                    'quantity' => $quantity,
                    'subtotal' => $itemSubtotal,
                    'created_at' => $createdAt,
                    'updated_at' => $createdAt,
                ]);
            }

            // Update order totals
            $tax = $subtotal * 0.17; // 17% VAT
            $shippingCost = $subtotal >= 200 ? 0 : 25;
            $total = $subtotal + $tax + $shippingCost;

            $order->update([
                'subtotal' => $subtotal,
                'tax' => $tax,
                'shipping_cost' => $shippingCost,
                'total' => $total,
            ]);

            $this->command->info("Created order: {$order->order_number}");
        }

        $this->command->info('✓ Sample orders created successfully!');
    }
}
