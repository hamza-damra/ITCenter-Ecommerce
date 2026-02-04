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
        Schema::table('banners', function (Blueprint $table) {
            $table->string('title_color', 7)->nullable()->after('title_he');
            $table->string('subtitle_color', 7)->nullable()->after('subtitle_he');
            $table->string('button_bg_color', 7)->nullable()->after('button_text_he');
            $table->string('button_text_color', 7)->nullable()->after('button_bg_color');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('banners', function (Blueprint $table) {
            $table->dropColumn([
                'title_color',
                'subtitle_color',
                'button_bg_color',
                'button_text_color',
            ]);
        });
    }
};
