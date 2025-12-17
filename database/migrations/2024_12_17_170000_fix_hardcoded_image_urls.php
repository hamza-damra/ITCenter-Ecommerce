<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Fix products with hardcoded localhost/IP URLs for images.
     * These URLs break when accessed from external networks.
     */
    public function up(): void
    {
        // Fix products with hardcoded local URLs in main_image
        // Set them to null so the model fallback to default image works
        DB::table('products')
            ->where('main_image', 'LIKE', '%localhost%')
            ->orWhere('main_image', 'LIKE', '%0.0.0.0%')
            ->orWhere('main_image', 'LIKE', '%127.0.0.1%')
            ->update(['main_image' => null]);

        // Delete product_images with hardcoded local URLs (can't set null due to NOT NULL constraint)
        if (Schema::hasTable('product_images')) {
            DB::table('product_images')
                ->where('image_path', 'LIKE', '%localhost%')
                ->orWhere('image_path', 'LIKE', '%0.0.0.0%')
                ->orWhere('image_path', 'LIKE', '%127.0.0.1%')
                ->delete();
        }

        // Fix categories table
        if (Schema::hasColumn('categories', 'image')) {
            DB::table('categories')
                ->where('image', 'LIKE', '%localhost%')
                ->orWhere('image', 'LIKE', '%0.0.0.0%')
                ->orWhere('image', 'LIKE', '%127.0.0.1%')
                ->update(['image' => null]);
        }

        // Fix brands table
        if (Schema::hasColumn('brands', 'logo')) {
            DB::table('brands')
                ->where('logo', 'LIKE', '%localhost%')
                ->orWhere('logo', 'LIKE', '%0.0.0.0%')
                ->orWhere('logo', 'LIKE', '%127.0.0.1%')
                ->update(['logo' => null]);
        }

        // Fix banners table
        if (Schema::hasColumn('banners', 'image_path')) {
            DB::table('banners')
                ->where('image_path', 'LIKE', '%localhost%')
                ->orWhere('image_path', 'LIKE', '%0.0.0.0%')
                ->orWhere('image_path', 'LIKE', '%127.0.0.1%')
                ->update(['image_path' => null]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Cannot reverse this migration as original URLs are lost
    }
};

