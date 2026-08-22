<?php

namespace Database\Seeders;

use App\Models\Reservation;
use App\Models\Table;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class ReservationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tables = Table::all();
        
        if ($tables->isEmpty()) {
            $this->command->error('Tidak ada data meja. Jalankan seeder meja terlebih dahulu.');
            return;
        }

        $names = [
            'Budi Santoso',
            'Siti Nurhaliza',
            'Ahmad Dahlan',
            'Dewi Lestari',
            'Eko Prasetyo',
            'Rani Wijaya',
            'Hendra Kusuma',
            'Maya Sari',
            'Rizki Ramadan',
            'Linda Hartono',
            'Agus Setiawan',
            'Fitri Amalia',
            'Bambang Hermawan',
            'Nadia Putri',
            'Irfan Hakim',
            'Putri Anggraini',
            'Doni Saputra',
            'Sri Wahyuni',
            'Farhan Maulana',
            'Indah Permata'
        ];

        $statuses = ['pending', 'confirmed', 'completed', 'canceled'];
        
        $reservations = [];
        
        for ($i = 0; $i < 20; $i++) {
            $date = Carbon::today()->addDays(rand(-5, 10));
            $hour = rand(11, 20);
            $minute = [0, 15, 30, 45][rand(0, 3)];
            
            // Status logic based on date
            if ($date->isFuture()) {
                $status = ['pending', 'confirmed'][rand(0, 1)];
            } elseif ($date->isToday()) {
                $status = ['pending', 'confirmed', 'completed'][rand(0, 2)];
            } else {
                $status = ['completed', 'canceled'][rand(0, 1)];
            }
            
            $reservations[] = [
                'table_id' => $tables->random()->id,
                'customer_name' => $names[$i],
                'phone_number' => '08' . rand(1000000000, 9999999999),
                'reservation_date' => $date->format('Y-m-d'),
                'reservation_time' => sprintf('%02d:%02d:00', $hour, $minute),
                'number_of_guests' => rand(1, 6),
                'status' => $status,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        Reservation::insert($reservations);
        
        $this->command->info('20 data reservasi dummy berhasil dibuat!');
    }
}