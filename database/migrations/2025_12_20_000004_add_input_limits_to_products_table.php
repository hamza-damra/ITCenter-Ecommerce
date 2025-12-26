<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * This migration ensures consistent column sizes for product names and descriptions
     * to prevent UI overflow issues.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Enforce name limits (120 chars max for safe UI rendering)
            $table->string('name_en', 120)->change();
            $table->string('name_ar', 120)->change();
            $table->string('name_he', 120)->nullable()->change();
            
            // Short descriptions already TEXT (safe, but we'll validate in app layer)
            // Full descriptions already LONGTEXT (safe for 3000 chars limit)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Revert to original VARCHAR(255) if needed
            $table->string('name_en', 255)->change();
            $table->string('name_ar', 255)->change();
            $table->string('name_he', 255)->nullable()->change();
        });
    }
};






