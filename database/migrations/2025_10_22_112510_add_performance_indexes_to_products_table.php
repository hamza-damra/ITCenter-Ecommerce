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
        Schema::table('products', function (Blueprint $table) {
            // Add indexes for better query performance
            $table->index(['is_active', 'is_featured'], 'idx_products_active_featured');
            $table->index(['is_active', 'is_new'], 'idx_products_active_new');
            $table->index(['is_active', 'is_bestseller'], 'idx_products_active_bestseller');
            $table->index(['is_active', 'sale_price'], 'idx_products_active_sale');
            $table->index(['category_id', 'is_active'], 'idx_products_category_active');
            $table->index(['brand_id', 'is_active'], 'idx_products_brand_active');
            $table->index(['stock_status', 'is_active'], 'idx_products_stock_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Drop the indexes
            $table->dropIndex('idx_products_active_featured');
            $table->dropIndex('idx_products_active_new');
            $table->dropIndex('idx_products_active_bestseller');
            $table->dropIndex('idx_products_active_sale');
            $table->dropIndex('idx_products_category_active');
            $table->dropIndex('idx_products_brand_active');
            $table->dropIndex('idx_products_stock_active');
        });
    }
};
