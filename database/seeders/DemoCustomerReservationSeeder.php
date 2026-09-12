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
            ['name' => 'Budi', 'phone' => '081200000001', 'email' => 'budi@gmail.com', 'password' => 'budi123'],
            ['name' => 'Siti', 'phone' => '081200000002', 'email' => 'siti@gmail.com', 'password' => 'siti123'],
            ['name' => 'Ahmad', 'phone' => '081200000003', 'email' => 'ahmad@gmail.com', 'password' => 'ahmad123'],
        ];

        foreach ($customers as $index => $data) {
            $user = User::updateOrCreate(
                ['phone' => $data['phone']],
                [
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'password' => Hash::make($data['password']),
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
                        'reserved_until' => $i === 0 ? $date->copy()->setTime(11 + $i, 0)->addHours(3) : null,
                        'notes' => 'Data dummy untuk pengujian.',
                        'total_amount' => 50000 + ($i * 10000),
                        'dp_amount' => 25000 + ($i * 5000),
                        'payment_status' => $i === 0 ? 'paid' : 'pending',
                        'payment_type' => $i % 2 === 0 ? 'qris' : 'transfer',
                        'payment_time' => $i === 0 ? now() : null,
                    ]
                );

                $reservation = Reservation::where('user_id', $user->id)
                    ->whereDate('reservation_date', $date->toDateString())
                    ->whereTime('reservation_time', sprintf('%02d:00:00', 11 + $i))
                    ->first();
                if ($reservation && \Illuminate\Support\Facades\Schema::hasTable('reservation_items')) {
                    $product = \App\Models\Product::orderBy('id')->skip(($index + $i) % max(1, \App\Models\Product::count()))->first();
                    if ($product) {
                        \App\Models\ReservationItem::updateOrCreate(
                            ['reservation_id' => $reservation->id, 'product_id' => $product->id],
                            ['qty' => 1 + $i, 'price' => $product->harga]
                        );
                    }
                }
            }
        }

        $this->command->info('3 akun demo dengan masing-masing 4 reservasi dibuat.');
    }
}
