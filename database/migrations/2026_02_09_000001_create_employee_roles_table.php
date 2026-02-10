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
        Schema::create('employee_roles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->json('permissions')->default('[]');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Expand the role enum to include 'employee'
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('customer', 'admin', 'employee') DEFAULT 'customer'");

        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('employee_role_id')->nullable()->after('role')
                ->constrained('employee_roles')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['employee_role_id']);
            $table->dropColumn('employee_role_id');
        });

        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('customer', 'admin') DEFAULT 'customer'");

        Schema::dropIfExists('employee_roles');
    }
};
