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
            $table->boolean('is_strong_offer')->default(false)->after('is_special_offer');
            $table->decimal('discount_percentage', 5, 2)->nullable()->after('is_strong_offer');
            
            // Add index for performance
            $table->index('is_strong_offer');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex(['is_strong_offer']);
            $table->dropColumn(['is_strong_offer', 'discount_percentage']);
        });
    }
};
