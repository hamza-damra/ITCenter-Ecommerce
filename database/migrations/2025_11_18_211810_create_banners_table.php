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
        Schema::create('banners', function (Blueprint $table) {
            $table->id();
            $table->string('title_en');
            $table->string('title_ar');
            $table->string('title_he')->nullable();
            $table->text('description_en')->nullable();
            $table->text('description_ar')->nullable();
            $table->text('description_he')->nullable();
            $table->string('image_url');
            $table->string('button_text_en')->nullable();
            $table->string('button_text_ar')->nullable();
            $table->string('button_text_he')->nullable();
            $table->enum('link_type', ['external', 'products', 'categories', 'category'])->default('products');
            $table->text('link_url')->nullable(); // للروابط الخارجية
            $table->unsignedBigInteger('category_id')->nullable(); // للفلترة حسب الفئة
            $table->json('filter_options')->nullable(); // لحفظ فلاتر إضافية (featured, new, bestseller, etc)
            $table->integer('display_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->enum('section', ['strong_offers', 'gift_ideas', 'hero'])->default('strong_offers');
            $table->timestamps();
            
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('banners');
    }
};
