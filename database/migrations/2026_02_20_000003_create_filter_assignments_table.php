<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('filter_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('filter_id')->constrained('filters')->cascadeOnDelete();
            $table->foreignId('category_id')->constrained('categories')->cascadeOnDelete();
            $table->boolean('inherit_to_children')->default(false);
            $table->timestamps();

            $table->unique(['filter_id', 'category_id']);
            $table->index('category_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('filter_assignments');
    }
};
