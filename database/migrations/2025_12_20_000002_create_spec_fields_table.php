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
        Schema::create('spec_fields', function (Blueprint $table) {
            $table->id();
            $table->foreignId('spec_template_id')->constrained('spec_templates')->onDelete('cascade');
            $table->string('key', 50); // slug, e.g., 'cpu', 'ram'
            $table->string('label_en', 100);
            $table->string('label_ar', 100)->nullable();
            $table->string('label_he', 100)->nullable();
            $table->enum('type', ['text', 'number', 'boolean', 'select'])->default('text');
            $table->json('options')->nullable(); // For select type: ["Option 1", "Option 2"]
            $table->string('unit', 30)->nullable(); // e.g., "GB", "GHz", "inches"
            $table->boolean('is_required')->default(false);
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            // Unique key per template
            $table->unique(['spec_template_id', 'key']);
            $table->index(['spec_template_id', 'sort_order']);
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('spec_fields');
    }
};






