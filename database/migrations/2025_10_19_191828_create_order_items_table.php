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
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->nullable()->constrained()->onDelete('set null');
            
            // Product snapshot at time of order
            $table->string('product_name_en');
            $table->string('product_name_ar');
            $table->string('product_name_he');
            $table->string('product_slug')->nullable();
            $table->string('product_image')->nullable();
            $table->string('product_sku')->nullable();
            
            // Pricing
            $table->decimal('price', 10, 2);
            $table->decimal('original_price', 10, 2)->nullable();
            $table->integer('quantity');
            $table->decimal('subtotal', 10, 2);
            
            $table->timestamps();
            
            $table->index('order_id');
            $table->index('product_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
