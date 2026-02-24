<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_filter_numeric_values', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->foreignId('filter_id')->constrained('filters')->cascadeOnDelete();
            $table->decimal('numeric_value', 12, 4);

            $table->unique(['product_id', 'filter_id']);
            $table->index('filter_id');
            $table->index('numeric_value');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_filter_numeric_values');
    }
};
