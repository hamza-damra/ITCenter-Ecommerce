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
        Schema::create('product_spec_values', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->foreignId('spec_field_id')->constrained('spec_fields')->onDelete('cascade');
            $table->text('value')->nullable(); // Stored as string, cast in UI based on field type
            $table->timestamps();
            
            // Each product can only have one value per spec field
            $table->unique(['product_id', 'spec_field_id']);
            $table->index('product_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_spec_values');
    }
};


