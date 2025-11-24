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
        $this->addIndexIfNotExists('products', ['is_active', 'is_featured'], 'idx_active_featured');
        $this->addIndexIfNotExists('products', ['is_active', 'is_new'], 'idx_active_new');
        $this->addIndexIfNotExists('products', ['is_active', 'is_bestseller'], 'idx_active_bestseller');
        $this->addIndexIfNotExists('products', ['is_active', 'sale_price'], 'idx_active_sale');
        $this->addIndexIfNotExists('products', 'stock_status', 'idx_stock_status');
        $this->addIndexIfNotExists('products', 'created_at', 'idx_created_at');

        // Check and add foreign key indexes if they don't exist
        $this->addIndexIfNotExists('products', 'brand_id', 'products_brand_id_index');
        $this->addIndexIfNotExists('products', 'category_id', 'products_category_id_index');

        // Categories table indexes
        $this->addIndexIfNotExists('categories', ['is_active', 'parent_id'], 'idx_active_parent');
        $this->addIndexIfNotExists('categories', 'order', 'idx_order');
        $this->addIndexIfNotExists('categories', ['parent_id', 'is_active', 'position'], 'idx_parent_active_position');

        // Brands table indexes
        $this->addIndexIfNotExists('brands', ['is_active', 'is_featured'], 'idx_active_featured');
        $this->addIndexIfNotExists('brands', 'order', 'idx_order');

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
    private function addIndexIfNotExists(string $table, string|array $column, string $indexName): void
    {
        // Skip if index already exists by checking if we can drop it
        try {
            DB::statement("SELECT 1 FROM sqlite_master WHERE type='index' AND name='{$indexName}'");
            $exists = DB::select("SELECT name FROM sqlite_master WHERE type='index' AND name=?", [$indexName]);
            if (!empty($exists)) {
                return; // Index exists, skip
            }
        } catch (\Exception $e) {
            // Continue to create index
        }
        
        try {
            Schema::table($table, function (Blueprint $table) use ($column, $indexName) {
                $table->index($column, $indexName);
            });
        } catch (\Exception $e) {
            // Index already exists or other error, ignore
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
        
        $this->dropIndexIfExists('categories', 'idx_parent_active_position');

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
        try {
            Schema::table($table, function (Blueprint $table) use ($indexName) {
                $table->dropIndex($indexName);
            });
        } catch (\Exception $e) {
            // Index doesn't exist, ignore
        }
    }
};
