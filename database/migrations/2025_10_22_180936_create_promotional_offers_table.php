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
        Schema::create('promotional_offers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->string('title_en');
            $table->string('title_ar');
            $table->string('title_he')->nullable();
            $table->decimal('original_price', 10, 2);
            $table->decimal('sale_price', 10, 2);
            $table->decimal('discount_amount', 10, 2);
            $table->integer('discount_percentage');
            $table->text('features_en')->nullable();
            $table->text('features_ar')->nullable();
            $table->text('features_he')->nullable();
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->boolean('is_active')->default(true);
            $table->integer('display_order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promotional_offers');
    }
};
