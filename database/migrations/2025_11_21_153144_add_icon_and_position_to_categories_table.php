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
        Schema::table('categories', function (Blueprint $table) {
            $table->string('icon')->nullable()->after('image');
            $table->integer('position')->default(0)->after('order');
            
            // Add indexes for performance
            $table->index('position');
            $table->index(['parent_id', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropIndex(['parent_id', 'is_active']);
            $table->dropIndex(['position']);
            $table->dropColumn(['icon', 'position']);
        });
    }
};
