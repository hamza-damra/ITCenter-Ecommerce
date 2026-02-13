<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Shipping Regions (e.g., West Bank, 1948 Territories)
        Schema::create('shipping_regions', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique(); // e.g., 'west_bank', 'interior_48'
            $table->string('name_en');
            $table->string('name_ar');
            $table->string('name_he');
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        // Shipping Cities within regions
        Schema::create('shipping_cities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shipping_region_id')->constrained('shipping_regions')->cascadeOnDelete();
            $table->string('key')->unique(); // e.g., 'nablus', 'haifa'
            $table->string('name_en');
            $table->string('name_ar');
            $table->string('name_he');
            $table->string('governorate_en');
            $table->string('governorate_ar');
            $table->string('governorate_he');
            $table->integer('postal_code_min'); // numeric part only (e.g., 400)
            $table->integer('postal_code_max'); // numeric part only (e.g., 499)
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->index('shipping_region_id');
            $table->index('is_active');
        });

        // Blocked postal code ranges (e.g., Gaza)
        Schema::create('shipping_blocked_ranges', function (Blueprint $table) {
            $table->id();
            $table->integer('postal_code_min');
            $table->integer('postal_code_max');
            $table->string('label_en');
            $table->string('label_ar');
            $table->string('label_he');
            $table->string('reason_en')->nullable();
            $table->string('reason_ar')->nullable();
            $table->string('reason_he')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Shipping settings (key-value store for dynamic config)
        Schema::create('shipping_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('type')->default('string'); // string, boolean, integer, json
            $table->string('description_en')->nullable();
            $table->string('description_ar')->nullable();
            $table->string('description_he')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shipping_settings');
        Schema::dropIfExists('shipping_blocked_ranges');
        Schema::dropIfExists('shipping_cities');
        Schema::dropIfExists('shipping_regions');
    }
};
