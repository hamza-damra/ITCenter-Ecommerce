<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * This migration adds support for storing promotional ad images in database
     * instead of file storage, similar to banners.
     */
    public function up(): void
    {
        Schema::table('promotional_ads', function (Blueprint $table) {
            // Image source type: 'database' (default) or 'url' for external URLs
            $table->enum('image_source', ['database', 'url'])
                ->default('database')
                ->after('image_path');
            
            // Store image data directly in database (LONGBLOB can store up to 4GB)
            $table->longText('image_data')->nullable()->after('image_source');
            
            // Store the original filename when uploading to database
            $table->string('image_filename')->nullable()->after('image_data');
            
            // Store the MIME type for proper content-type headers
            $table->string('image_mime_type', 100)->nullable()->after('image_filename');
            
            // Make image_path nullable since we now have database storage
            $table->string('image_path')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('promotional_ads', function (Blueprint $table) {
            $table->dropColumn(['image_source', 'image_data', 'image_filename', 'image_mime_type']);
            
            // Restore image_path to required
            $table->string('image_path')->nullable(false)->change();
        });
    }
};

