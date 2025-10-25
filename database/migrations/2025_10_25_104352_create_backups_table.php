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
        Schema::create('backups', function (Blueprint $table) {
            $table->id();
            $table->string('filename')->unique();
            $table->string('type')->default('database'); // database, modules
            $table->bigInteger('size')->default(0);
            $table->timestamp('expires_at')->nullable(); // null = never expires
            $table->string('created_by')->nullable(); // admin email/name
            $table->json('metadata')->nullable(); // store additional info (modules, etc)
            $table->timestamps();
            
            $table->index('expires_at');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('backups');
    }
};
