<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Models\Table;
use App\Models\Reservation;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Otomatisasi status meja - 2 Shift (Siang & Malam)

// Jam 07:00 - Buka Shift Siang (semua meja menjadi "Tersedia")
Schedule::call(function () {
    Table::query()->update([
        'status_meja' => 'tersedia',
        'active_session_token' => null,
        'qr_available' => true,
    ]);
    \Illuminate\Support\Facades\Log::info('Shift Siang: Meja diatur otomatis ke Tersedia (07:00).');
})->dailyAt('07:00');

// Jam 17:00 - Tutup Shift Siang / Istirahat (semua meja menjadi "Terisi")
Schedule::call(function () {
    Table::query()->update([
        'status_meja' => 'terisi',
        'active_session_token' => null,
        'qr_available' => false,
    ]);
    \Illuminate\Support\Facades\Log::info('Istirahat: Meja diatur otomatis ke Terisi (17:00).');
})->dailyAt('17:00');

// Jam 19:00 - Buka Shift Malam (semua meja menjadi "Tersedia")
Schedule::call(function () {
    Table::query()->update([
        'status_meja' => 'tersedia',
        'active_session_token' => null,
        'qr_available' => true,
    ]);
    \Illuminate\Support\Facades\Log::info('Shift Malam: Meja diatur otomatis ke Tersedia (19:00).');
})->dailyAt('19:00');

// Jam 23:00 - Tutup (semua meja menjadi "Terisi")
Schedule::call(function () {
    Table::query()->update([
        'status_meja' => 'terisi',
        'active_session_token' => null,
        'qr_available' => false,
    ]);
    \Illuminate\Support\Facades\Log::info('Tutup: Meja diatur otomatis ke Terisi (23:00).');
})->dailyAt('23:00');

// Cek setiap 15 menit untuk melepaskan reservasi yang sudah melewati batas waktu 3 jam
Schedule::call(function () {
    $expiredReservations = Reservation::expired()->get();
    
    foreach ($expiredReservations as $reservation) {
        // Update status reservasi menjadi completed
        $reservation->update(['status' => 'completed']);
        
        // Update status meja menjadi tersedia
        if ($reservation->table) {
            $reservation->table->update([
                'status_meja' => 'tersedia',
                'qr_available' => true,
                'active_session_token' => null
            ]);
        }
        
        \Illuminate\Support\Facades\Log::info('Reservasi expired dan meja dibebaskan', [
            'reservation_id' => $reservation->id,
            'table_id' => $reservation->table_id,
            'customer_name' => $reservation->customer_name
        ]);
    }
    
    if ($expiredReservations->count() > 0) {
        \Illuminate\Support\Facades\Log::info('Total reservasi expired yang diproses: ' . $expiredReservations->count());
    }
})->everyFifteenMinutes();
