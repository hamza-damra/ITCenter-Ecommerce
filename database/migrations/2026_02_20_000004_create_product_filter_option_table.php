<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_filter_option', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->foreignId('filter_option_id')->constrained('filter_options')->cascadeOnDelete();

            $table->unique(['product_id', 'filter_option_id']);
            $table->index('filter_option_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_filter_option');
    }
};
