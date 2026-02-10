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
        Schema::create('home_sections', function (Blueprint $table) {
            $table->id();
            $table->string('type', 50); // hero_banner, category_carousel, featured_products, etc.
            $table->string('title_en', 120)->nullable();
            $table->string('title_ar', 120)->nullable();
            $table->string('title_he', 120)->nullable();
            $table->string('subtitle_en', 255)->nullable();
            $table->string('subtitle_ar', 255)->nullable();
            $table->string('subtitle_he', 255)->nullable();
            $table->integer('display_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->json('settings')->nullable(); // per-section config (auto_scroll, max_products, colors, etc.)
            $table->timestamps();

            $table->index('display_order');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('home_sections');
    }
};
