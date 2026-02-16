<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('site_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('type')->default('string'); // string, integer, boolean, json
            $table->string('group')->default('general'); // general, images, security
            $table->timestamps();
        });

        // Seed default image settings
        DB::table('site_settings')->insert([
            [
                'key' => 'max_image_size_kb',
                'value' => '5120',
                'type' => 'integer',
                'group' => 'images',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'allowed_image_formats',
                'value' => 'jpg,jpeg,png,webp',
                'type' => 'string',
                'group' => 'images',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'max_additional_images',
                'value' => '10',
                'type' => 'integer',
                'group' => 'images',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'image_quality',
                'value' => '80',
                'type' => 'integer',
                'group' => 'images',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'convert_to_webp',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'images',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'max_image_width',
                'value' => '1920',
                'type' => 'integer',
                'group' => 'images',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'max_image_height',
                'value' => '1080',
                'type' => 'integer',
                'group' => 'images',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('site_settings');
    }
};
