<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Allows admin to dynamically control which built-in filter sections
     * (Status, Brand, Strong Offers, Price) are shown and in what order.
     */
    public function up(): void
    {
        Schema::create('filter_section_settings', function (Blueprint $table) {
            $table->id();
            $table->string('section_key', 50)->unique(); // status, brand, strong_offers, price
            $table->boolean('is_enabled')->default(true);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });

        // Seed default sections
        $sections = [
            ['section_key' => 'status', 'is_enabled' => true, 'sort_order' => 0],
            ['section_key' => 'strong_offers', 'is_enabled' => true, 'sort_order' => 1],
            ['section_key' => 'brand', 'is_enabled' => true, 'sort_order' => 2],
            ['section_key' => 'price', 'is_enabled' => true, 'sort_order' => 3],
        ];
        foreach ($sections as $s) {
            DB::table('filter_section_settings')->insert(array_merge($s, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('filter_section_settings');
    }
};
