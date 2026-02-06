<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Insert auto_backup_interval setting (default: daily)
        DB::table('backup_settings')->insertOrIgnore([
            'key' => 'auto_backup_interval',
            'value' => 'daily',
            'type' => 'string',
            'description' => 'Auto backup interval (e.g., 1_minute, 5_minutes, 15_minutes, 30_minutes, hourly, 6_hours, 12_hours, daily, weekly, monthly, disabled)',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('backup_settings')->where('key', 'auto_backup_interval')->delete();
    }
};
