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
        Schema::create('promotional_ads', function (Blueprint $table) {
            $table->id();
            $table->string('image_path');
            $table->enum('position', ['left', 'right']);
            $table->string('link')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            // Add indexes for frequently queried columns
            $table->index('position');
            $table->index('is_active');
            $table->index(['position', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promotional_ads');
    }
};
