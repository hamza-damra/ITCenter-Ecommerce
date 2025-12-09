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
        // Tags table
        Schema::create('tags', function (Blueprint $table) {
            $table->id();
            $table->string('name_en');
            $table->string('name_ar');
            $table->string('name_he')->nullable();
            $table->string('slug')->unique();
            $table->string('color')->default('#3b82f6'); // Badge color
            $table->string('icon')->nullable(); // FontAwesome icon class
            $table->integer('position')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['is_active', 'position']);
        });

        // Pivot table for product-tag relationship
        Schema::create('product_tag', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('tag_id')->constrained()->onDelete('cascade');
            $table->timestamps();
            
            $table->unique(['product_id', 'tag_id']);
            $table->index('tag_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_tag');
        Schema::dropIfExists('tags');
    }
};
