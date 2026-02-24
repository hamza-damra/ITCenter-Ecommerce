<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('filter_options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('filter_id')->constrained('filters')->cascadeOnDelete();
            $table->string('label_en');
            $table->string('label_ar')->nullable();
            $table->string('label_he')->nullable();
            $table->string('value_slug');
            $table->string('color_code', 30)->nullable();
            $table->string('icon', 100)->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['filter_id', 'value_slug']);
            $table->index('is_active');
            $table->index('sort_order');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('filter_options');
    }
};
