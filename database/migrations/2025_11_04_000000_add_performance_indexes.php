<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations - Add performance indexes
     */
    public function up(): void
    {
        // Products table indexes
        Schema::table('products', function (Blueprint $table) {
            // Composite indexes for common filter queries
            $table->index(['is_active', 'is_featured'], 'idx_active_featured');
            $table->index(['is_active', 'is_new'], 'idx_active_new');
            $table->index(['is_active', 'is_bestseller'], 'idx_active_bestseller');
            $table->index(['is_active', 'sale_price'], 'idx_active_sale');
            $table->index(['stock_status'], 'idx_stock_status');
            $table->index(['created_at'], 'idx_created_at');
        });

        // Check and add foreign key indexes if they don't exist
        $this->addIndexIfNotExists('products', 'brand_id', 'products_brand_id_index');
        $this->addIndexIfNotExists('products', 'category_id', 'products_category_id_index');

        // Categories table indexes
        Schema::table('categories', function (Blueprint $table) {
            $table->index(['is_active', 'parent_id'], 'idx_active_parent');
            $table->index(['order'], 'idx_order');
        });

        // Brands table indexes
        Schema::table('brands', function (Blueprint $table) {
            $table->index(['is_active', 'is_featured'], 'idx_active_featured');
            $table->index(['order'], 'idx_order');
        });

        // Cart items table indexes
        $this->addIndexIfNotExists('cart_items', 'user_id', 'idx_user_id');
        $this->addIndexIfNotExists('cart_items', 'session_id', 'idx_session_id');
        $this->addIndexIfNotExists('cart_items', 'product_id', 'idx_product_id');

        // Favorites table indexes
        if (Schema::hasTable('favorites')) {
            $this->addIndexIfNotExists('favorites', 'user_id', 'idx_fav_user_id');
            $this->addIndexIfNotExists('favorites', 'session_id', 'idx_fav_session_id');
            $this->addIndexIfNotExists('favorites', 'product_id', 'idx_fav_product_id');
        }
    }

    /**
     * Add index if it doesn't exist
     */
    private function addIndexIfNotExists(string $table, string $column, string $indexName): void
    {
        $indexes = DB::select("SHOW INDEX FROM {$table} WHERE Key_name = ?", [$indexName]);
        
        if (empty($indexes)) {
            Schema::table($table, function (Blueprint $table) use ($column, $indexName) {
                $table->index($column, $indexName);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex('idx_active_featured');
            $table->dropIndex('idx_active_new');
            $table->dropIndex('idx_active_bestseller');
            $table->dropIndex('idx_active_sale');
            $table->dropIndex('idx_stock_status');
            $table->dropIndex('idx_created_at');
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->dropIndex('idx_active_parent');
            $table->dropIndex('idx_order');
        });

        Schema::table('brands', function (Blueprint $table) {
            $table->dropIndex('idx_active_featured');
            $table->dropIndex('idx_order');
        });

        // Drop cart_items indexes
        $this->dropIndexIfExists('cart_items', 'idx_user_id');
        $this->dropIndexIfExists('cart_items', 'idx_session_id');
        $this->dropIndexIfExists('cart_items', 'idx_product_id');

        // Drop favorites indexes
        if (Schema::hasTable('favorites')) {
            $this->dropIndexIfExists('favorites', 'idx_fav_user_id');
            $this->dropIndexIfExists('favorites', 'idx_fav_session_id');
            $this->dropIndexIfExists('favorites', 'idx_fav_product_id');
        }
    }

    /**
     * Drop index if it exists
     */
    private function dropIndexIfExists(string $table, string $indexName): void
    {
        $indexes = DB::select("SHOW INDEX FROM {$table} WHERE Key_name = ?", [$indexName]);
        
        if (!empty($indexes)) {
            Schema::table($table, function (Blueprint $table) use ($indexName) {
                $table->dropIndex($indexName);
            });
        }
    }
};
