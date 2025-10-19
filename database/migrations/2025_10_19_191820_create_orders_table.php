<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('session_id')->nullable();
            
            // Customer Information
            $table->string('customer_name');
            $table->string('customer_email');
            $table->string('customer_phone');
            
            // Shipping Address
            $table->string('shipping_address');
            $table->string('shipping_city');
            $table->string('shipping_state')->nullable();
            $table->string('shipping_postal_code')->nullable();
            $table->string('shipping_country')->default('Palestine');
            
            // Order Details
            $table->decimal('subtotal', 10, 2);
            $table->decimal('tax', 10, 2)->default(0);
            $table->decimal('shipping_cost', 10, 2)->default(0);
            $table->decimal('discount', 10, 2)->default(0);
            $table->decimal('total', 10, 2);
            
            // Status
            $table->enum('status', ['pending', 'processing', 'shipped', 'delivered', 'cancelled'])->default('pending');
            $table->enum('payment_status', ['pending', 'paid', 'failed', 'refunded'])->default('pending');
            $table->string('payment_method')->default('cash_on_delivery');
            
            // Additional Info
            $table->text('notes')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('shipped_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            $table->index('order_number');
            $table->index('user_id');
            $table->index('session_id');
            $table->index('status');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
