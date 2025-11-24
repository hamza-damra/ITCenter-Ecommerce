<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('attribute_values', function (Blueprint $table) {
            // Add multi-language support
            $table->string('value_en')->after('value');
            $table->string('value_ar')->after('value_en');
            $table->string('value_he')->after('value_ar');
            
            // Add slug for URL filtering
            $table->string('slug')->after('value_he');
            
            // Add index for performance
            $table->index('slug');
        });
        
        // Migrate existing data from 'value' to multi-language fields and generate slugs
        $attributeValues = DB::table('attribute_values')->get();
        foreach ($attributeValues as $attributeValue) {
            DB::table('attribute_values')
                ->where('id', $attributeValue->id)
                ->update([
                    'value_en' => $attributeValue->value,
                    'value_ar' => $attributeValue->value,
                    'value_he' => $attributeValue->value,
                    'slug' => Str::slug($attributeValue->value . '-' . $attributeValue->id),
                ]);
        }
        
        // Drop old value column
        Schema::table('attribute_values', function (Blueprint $table) {
            $table->dropColumn('value');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attribute_values', function (Blueprint $table) {
            // Restore old value column
            $table->string('value')->after('attribute_id');
        });
        
        // Migrate data back from value_en to value
        DB::statement('UPDATE attribute_values SET value = value_en');
        
        Schema::table('attribute_values', function (Blueprint $table) {
            $table->dropIndex(['slug']);
            $table->dropColumn(['value_en', 'value_ar', 'value_he', 'slug']);
        });
    }
};
