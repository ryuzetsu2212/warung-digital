<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Reservation;

class ReleaseExpiredReservations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reservations:release-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Bebaskan meja dari reservasi yang sudah melewati batas waktu 3 jam';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Memeriksa reservasi yang sudah expired...');
        
        $expiredReservations = Reservation::expired()->get();
        
        if ($expiredReservations->isEmpty()) {
            $this->info('Tidak ada reservasi yang expired.');
            return 0;
        }
        
        $this->info("Ditemukan {$expiredReservations->count()} reservasi yang sudah melewati batas waktu 3 jam.");
        
        foreach ($expiredReservations as $reservation) {
            $tableModel = \App\Models\Table::find($reservation->table_id);
            $this->line("- Reservasi #{$reservation->id} ({$reservation->customer_name}) di Meja {$tableModel->nomor_meja}");

            // Update status meja menjadi tersedia
            if ($tableModel) {
                $tableModel->update([
                    'status_meja' => 'tersedia',
                    'qr_available' => true,
                    'active_session_token' => null
                ]);

                $this->info("  ✓ Meja {$tableModel->nomor_meja} telah dibebaskan");
            }
            
            $this->info("  ✓ Meja {$reservation->table->nomor_meja} telah dibebaskan");
        }
        
        $this->info('Selesai!');
        
        return 0;
    }
}