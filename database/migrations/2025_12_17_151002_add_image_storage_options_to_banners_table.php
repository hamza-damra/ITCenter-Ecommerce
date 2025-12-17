<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * This migration adds support for storing banner images in multiple ways:
     * - 'file': Traditional file storage (backward compatible)
     * - 'database': Store image directly in database as base64
     * - 'url': External URL to fetch image from internet
     */
    public function up(): void
    {
        Schema::table('banners', function (Blueprint $table) {
            // Image source type: 'file', 'database', or 'url'
            $table->enum('image_source', ['file', 'database', 'url'])
                ->default('file')
                ->after('image_path');
            
            // Store image data directly in database (LONGBLOB can store up to 4GB)
            $table->longText('image_data')->nullable()->after('image_source');
            
            // Store the original filename when uploading to database
            $table->string('image_filename')->nullable()->after('image_data');
            
            // Store the MIME type for proper content-type headers
            $table->string('image_mime_type', 100)->nullable()->after('image_filename');
            
            // Make image_path nullable since we now have alternative storage options
            $table->string('image_path')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('banners', function (Blueprint $table) {
            $table->dropColumn(['image_source', 'image_data', 'image_filename', 'image_mime_type']);
            
            // Restore image_path to required
            $table->string('image_path')->nullable(false)->change();
        });
    }
};
