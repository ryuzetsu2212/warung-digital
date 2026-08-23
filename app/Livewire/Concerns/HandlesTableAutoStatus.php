<?php

namespace App\Livewire\Concerns;

use App\Models\Table;

trait HandlesTableAutoStatus
{
    /**
     * Atur status meja secara otomatis berdasarkan jam operasional:
     * - Shift Siang: 07:00 - 17:00 = semua meja "tersedia" (QR aktif)
     * - Istirahat: 17:00 - 19:00 = semua meja "terisi" (QR nonaktif)
     * - Shift Malam: 19:00 - 23:00 = semua meja "tersedia" (QR aktif)
     * - Tutup: 23:00 - 07:00 = semua meja "terisi" (QR nonaktif)
     *
     * PENTING: Hanya mengatur meja yang TIDAK memiliki pesanan aktif (belum dibayar).
     * Meja dengan pesanan aktif tetap dipertahankan statusnya.
     *
     * Dipanggil setiap halaman staff dimuat sebagai fallback
     * ketika scheduler/cron belum berjalan.
     */
    protected function checkAutoTableStatus(): void
    {
        $currentHour = now()->hour;
        
        // Shift Siang: 07:00 - 17:00 atau Shift Malam: 19:00 - 23:00
        $isOpen = ($currentHour >= 7 && $currentHour < 17) || ($currentHour >= 19 && $currentHour < 23);

        $this->applyTableStatus($isOpen ? 'tersedia' : 'terisi', $isOpen);
    }

    protected function applyTableStatus(string $status, bool $isOpen): void
    {
        // Update qr_available untuk SEMUA meja berdasarkan jam operasional
        // Ini mengontrol apakah QR code bisa digunakan untuk pesanan baru
        Table::query()->update([
            'qr_available' => $isOpen,
        ]);

        // Untuk status_meja, hanya update meja yang TIDAK memiliki:
        // 1. Pesanan aktif (belum lunas)
        // 2. Active session (pelanggan sedang browsing menu)
        
        // Dapatkan ID meja yang memiliki pesanan aktif (belum lunas)
        $tablesWithActiveOrders = Table::whereHas('orders', function ($query) {
            $query->where('status_pembayaran', '!=', 'lunas')
                  ->orWhereNull('status_pembayaran');
        })->pluck('id');

        // Dapatkan ID meja yang memiliki active session (pelanggan sedang browsing)
        $tablesWithActiveSessions = Table::whereNotNull('active_session_token')->pluck('id');

        // Gabungkan kedua list (meja yang harus dipertahankan status_meja nya)
        $tablesToProtect = $tablesWithActiveOrders->merge($tablesWithActiveSessions)->unique();

        // Update status_meja hanya untuk meja kosong (tidak ada pesanan aktif & tidak ada sesi aktif)
        Table::query()
            ->whereNotIn('id', $tablesToProtect)
            ->update([
                'status_meja' => $status,
                'active_session_token' => null,
            ]);

        // Notification message removed - no successMessage set
    }
}
