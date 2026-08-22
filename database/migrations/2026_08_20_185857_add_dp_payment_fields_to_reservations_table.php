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
        Schema::table('reservations', function (Blueprint $table) {
            $table->decimal('total_amount', 10, 2)->nullable();
            $table->decimal('dp_amount', 10, 2)->nullable();
            $table->string('payment_status')->default('unpaid');
            $table->string('snap_token')->nullable();
            $table->string('payment_type')->nullable();
            $table->timestamp('payment_time')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->dropColumn(['total_amount', 'dp_amount', 'payment_status', 'snap_token', 'payment_type', 'payment_time']);
        });
    }
};
