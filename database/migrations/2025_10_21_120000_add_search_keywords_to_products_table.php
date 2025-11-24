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
        Schema::table('products', function (Blueprint $table) {
            $table->text('search_keywords')->nullable()->after('meta_keywords');
            // Use fulltext index for TEXT columns (MySQL only)
            if (config('database.default') !== 'sqlite') {
                $table->fullText('search_keywords');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (config('database.default') !== 'sqlite') {
                $table->dropIndex(['search_keywords']);
            }
            $table->dropColumn('search_keywords');
        });
    }
};
