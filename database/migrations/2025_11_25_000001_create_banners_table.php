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
            $table->string('image_path');
            $table->string('title_en')->nullable();
            $table->string('title_ar')->nullable();
            $table->string('title_he')->nullable();
            $table->text('subtitle_en')->nullable();
            $table->text('subtitle_ar')->nullable();
            $table->text('subtitle_he')->nullable();
            $table->string('link')->nullable();
            $table->string('button_text_en', 100)->nullable();
            $table->string('button_text_ar', 100)->nullable();
            $table->string('button_text_he', 100)->nullable();
            $table->integer('display_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            // Add indexes for frequently queried columns
            $table->index('display_order');
            $table->index('is_active');
            $table->index(['is_active', 'display_order']);
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
