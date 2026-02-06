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
        Schema::table('promotional_ads', function (Blueprint $table) {
            // Title fields (multilingual)
            $table->string('title_en')->nullable()->after('is_active');
            $table->string('title_ar')->nullable()->after('title_en');
            $table->string('title_he')->nullable()->after('title_ar');
            $table->string('title_color', 7)->nullable()->after('title_he');
            $table->string('title_font_size', 20)->nullable()->after('title_color');
            
            // Subtitle fields (multilingual)
            $table->string('subtitle_en')->nullable()->after('title_font_size');
            $table->string('subtitle_ar')->nullable()->after('subtitle_en');
            $table->string('subtitle_he')->nullable()->after('subtitle_ar');
            $table->string('subtitle_color', 7)->nullable()->after('subtitle_he');
            $table->string('subtitle_font_size', 20)->nullable()->after('subtitle_color');
            
            // Button fields (multilingual)
            $table->string('button_text_en', 100)->nullable()->after('subtitle_font_size');
            $table->string('button_text_ar', 100)->nullable()->after('button_text_en');
            $table->string('button_text_he', 100)->nullable()->after('button_text_ar');
            $table->string('button_bg_color', 7)->nullable()->after('button_text_he');
            $table->string('button_text_color', 7)->nullable()->after('button_bg_color');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('promotional_ads', function (Blueprint $table) {
            $table->dropColumn([
                'title_en', 'title_ar', 'title_he', 'title_color', 'title_font_size',
                'subtitle_en', 'subtitle_ar', 'subtitle_he', 'subtitle_color', 'subtitle_font_size',
                'button_text_en', 'button_text_ar', 'button_text_he', 'button_bg_color', 'button_text_color',
            ]);
        });
    }
};
