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
        // Add approved_at to orders table
        Schema::table('orders', function (Blueprint $table) {
            $table->timestamp('approved_at')->nullable()->after('cancelled_at');
        });

        // Create order_cancellations table
        Schema::create('order_cancellations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('reason');
            $table->text('note')->nullable();
            $table->timestamps();

            $table->index('order_id');
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_cancellations');

        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('approved_at');
        });
    }
};
