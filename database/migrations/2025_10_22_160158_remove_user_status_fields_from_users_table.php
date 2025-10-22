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
        Schema::table('users', function (Blueprint $table) {
            // Set all users to active before dropping the column
            DB::table('users')->update(['status' => 'active']);
            
            // Drop user status management columns
            $table->dropColumn(['status_reason', 'suspended_at', 'banned_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Restore columns if needed
            $table->text('status_reason')->nullable()->after('status');
            $table->timestamp('suspended_at')->nullable()->after('status_reason');
            $table->timestamp('banned_at')->nullable()->after('suspended_at');
        });
    }
};
