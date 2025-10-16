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
        Schema::table('favorites', function (Blueprint $table) {
            // Drop foreign key constraint
            $table->dropForeign(['user_id']);
            
            // Drop the old unique constraint
            $table->dropUnique(['user_id', 'product_id']);
        });
        
        Schema::table('favorites', function (Blueprint $table) {
            // Make user_id nullable to support guest users
            $table->unsignedBigInteger('user_id')->nullable()->change();
            
            // Add session_id for guest users
            $table->string('session_id')->nullable()->after('user_id');
            
            // Add index for session_id for faster queries
            $table->index('session_id');
            
            // Re-add the foreign key constraint
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('favorites', function (Blueprint $table) {
            // Drop foreign key
            $table->dropForeign(['user_id']);
            
            // Remove session_id column
            $table->dropIndex(['session_id']);
            $table->dropColumn('session_id');
        });
        
        Schema::table('favorites', function (Blueprint $table) {
            // Make user_id not nullable again
            $table->unsignedBigInteger('user_id')->nullable(false)->change();
            
            // Restore the foreign key
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            
            // Restore the unique constraint
            $table->unique(['user_id', 'product_id']);
        });
    }
};
