<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (!DB::getSchemaBuilder()->hasTable('reservations')) {
            return;
        }

        // Bersihkan semua reservasi demo: apapun statusnya jadi selesai + lunas.
        DB::table('reservations')
            ->whereIn('phone_number', ['081200000001', '081200000002', '081200000003'])
            ->where(function ($q) {
                $q->where('status', '!=', 'completed')
                  ->orWhere('payment_status', '!=', 'paid');
            })
            ->update([
                'status' => 'completed',
                'payment_status' => 'paid',
                'payment_time' => now(),
                'updated_at' => now(),
            ]);
    }

    public function down(): void
    {
        // No-op: perubahan data tidak dikembalikan.
    }
};
