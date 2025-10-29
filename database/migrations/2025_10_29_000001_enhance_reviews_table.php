<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            // Add status enum field (pending, approved, rejected)
            $table->enum('status', ['pending', 'approved', 'rejected'])
                ->default('approved')
                ->after('is_approved');
            
            // Add images support for reviews (JSON array of image paths)
            $table->json('images')->nullable()->after('comment');
            
            // Add index for status for faster filtering
            $table->index('status');
            
            // Add composite index for common queries
            $table->index(['product_id', 'status', 'created_at']);
        });
        
        // Migrate existing data: set all reviews to approved and is_approved=true
        DB::table('reviews')->update(['status' => 'approved', 'is_approved' => true]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropIndex(['product_id', 'status', 'created_at']);
            $table->dropIndex(['status']);
            $table->dropColumn(['status', 'images']);
        });
    }
};

