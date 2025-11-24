<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('attributes', function (Blueprint $table) {
            // Add multi-language support
            $table->string('name_en')->after('name');
            $table->string('name_ar')->after('name_en');
            $table->string('name_he')->after('name_ar');
            
            // Add new fields
            $table->string('unit', 50)->nullable()->after('type');
            $table->boolean('is_filterable')->default(true)->after('unit');
            
            // Add index for performance
            $table->index('is_filterable');
        });
        
        // Migrate existing data from 'name' to 'name_en'
        DB::statement('UPDATE attributes SET name_en = name, name_ar = name, name_he = name');
        
        // Drop old name column
        Schema::table('attributes', function (Blueprint $table) {
            $table->dropColumn('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attributes', function (Blueprint $table) {
            // Restore old name column
            $table->string('name')->after('id');
        });
        
        // Migrate data back from name_en to name
        DB::statement('UPDATE attributes SET name = name_en');
        
        Schema::table('attributes', function (Blueprint $table) {
            $table->dropIndex(['is_filterable']);
            $table->dropColumn(['name_en', 'name_ar', 'name_he', 'unit', 'is_filterable']);
        });
    }
};
