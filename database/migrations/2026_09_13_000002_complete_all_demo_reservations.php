<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Bersihkan data demo: semua reservasi milik akun demo (Budi/Siti/Ahmad)
        // yang masih pending/confirmed ditandai selesai + lunas.
        if (!DB::getSchemaBuilder()->hasTable('reservations')) {
            return;
        }

        DB::table('reservations')
            ->whereIn('phone_number', ['081200000001', '081200000002', '081200000003'])
            ->whereIn('status', ['pending', 'confirmed'])
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
