<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * This migration enforces database integrity by:
     * 1. Making user_id NOT NULL (required for all orders)
     * 2. Removing session_id column (no longer needed)
     * 
     * IMPORTANT: Run this only after ensuring all existing orders have valid user_id
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // First, delete any ghost orders with null user_id (cleanup)
            DB::table('orders')->whereNull('user_id')->delete();
            
            // Drop the existing foreign key constraint first
            $table->dropForeign(['user_id']);
            
            // Make user_id required (NOT NULL)
            $table->unsignedBigInteger('user_id')->nullable(false)->change();
            
            // Re-add the foreign key constraint without onDelete('set null')
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
        
        // Drop session_id index and column separately for SQLite compatibility
        Schema::table('orders', function (Blueprint $table) {
            // Drop the index first if it exists
            if (Schema::hasColumn('orders', 'session_id')) {
                $table->dropIndex(['session_id']);
            }
        });
        
        Schema::table('orders', function (Blueprint $table) {
            // Remove session_id column (no longer tracking guest orders)
            if (Schema::hasColumn('orders', 'session_id')) {
                $table->dropColumn('session_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Restore session_id column
            $table->string('session_id')->nullable()->after('user_id');
            
            // Drop the foreign key constraint
            $table->dropForeign(['user_id']);
            
            // Make user_id nullable again
            $table->unsignedBigInteger('user_id')->nullable()->change();
            
            // Re-add the foreign key with set null
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }
};
