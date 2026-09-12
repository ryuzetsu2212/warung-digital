<?php

namespace Database\Seeders;

use App\Models\Reservation;
use App\Models\Table;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DemoCustomerReservationSeeder extends Seeder
{
    public function run(): void
    {
        $tables = Table::orderBy('id')->get();
        if ($tables->isEmpty()) {
            $this->command->warn('Tidak ada meja; reservasi dummy dilewati.');
            return;
        }

        $customers = [
            ['name' => 'Pelanggan Demo Satu', 'phone' => '081200000001', 'email' => 'demo1@example.test'],
            ['name' => 'Pelanggan Demo Dua', 'phone' => '081200000002', 'email' => 'demo2@example.test'],
            ['name' => 'Pelanggan Demo Tiga', 'phone' => '081200000003', 'email' => 'demo3@example.test'],
        ];

        foreach ($customers as $index => $data) {
            $user = User::updateOrCreate(
                ['phone' => $data['phone']],
                [
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'password' => Hash::make('Demo12345'),
                    'role' => 'customer',
                ]
            );

            for ($i = 0; $i < 4; $i++) {
                $date = Carbon::today()->addDays(($index * 4) + $i + 1);
                Reservation::updateOrCreate(
                    [
                        'user_id' => $user->id,
                        'reservation_date' => $date->toDateString(),
                        'reservation_time' => sprintf('%02d:00:00', 11 + $i),
                    ],
                    [
                        'table_id' => $tables[($index * 4 + $i) % $tables->count()]->id,
                        'customer_name' => $user->name,
                        'phone_number' => $user->phone,
                        'number_of_guests' => 2 + $i,
                        'status' => $i === 0 ? 'confirmed' : 'pending',
                        'reservation_end_time' => sprintf('%02d:00:00', 12 + $i),
                        'notes' => 'Data dummy untuk pengujian.',
                    ]
                );
            }
        }

        $this->command->info('3 akun demo dengan masing-masing 4 reservasi dibuat.');
    }
}
