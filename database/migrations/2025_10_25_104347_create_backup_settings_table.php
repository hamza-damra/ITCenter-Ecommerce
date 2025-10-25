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
        Schema::create('backup_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('type')->default('string'); // string, integer, boolean, json
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Insert default settings
        DB::table('backup_settings')->insert([
            [
                'key' => 'auto_cleanup_enabled',
                'value' => '1',
                'type' => 'boolean',
                'description' => 'Enable automatic cleanup of expired backups',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'default_retention_days',
                'value' => '30',
                'type' => 'integer',
                'description' => 'Default retention period for automatic backups (in days)',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'max_backups',
                'value' => '10',
                'type' => 'integer',
                'description' => 'Maximum number of backups to keep',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('backup_settings');
    }
};
