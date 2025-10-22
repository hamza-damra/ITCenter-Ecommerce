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
        Schema::table('categories', function (Blueprint $table) {
            // Add indexes for better query performance
            $table->index(['is_active', 'parent_id'], 'idx_categories_active_parent');
            $table->index(['is_active', 'order'], 'idx_categories_active_order');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            // Drop the indexes
            $table->dropIndex('idx_categories_active_parent');
            $table->dropIndex('idx_categories_active_order');
        });
    }
};
