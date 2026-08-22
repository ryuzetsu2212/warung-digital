<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $products = DB::table('products')->get();
        if ($products->isEmpty()) {
            $products = collect([
                (object)['id' => 1, 'nama' => 'Sate Ayam Madura', 'kategori' => 'makanan', 'harga' => 30000],
                (object)['id' => 2, 'nama' => 'Ayam Geprek Sambal Matah', 'kategori' => 'makanan', 'harga' => 22000],
                (object)['id' => 3, 'nama' => 'Nasi Rawon Daging Sapi', 'kategori' => 'makanan', 'harga' => 28000],
                (object)['id' => 4, 'nama' => 'Bakso Urat Sapi', 'kategori' => 'makanan', 'harga' => 18000],
                (object)['id' => 5, 'nama' => 'Es Teh Manis', 'kategori' => 'minuman', 'harga' => 5000],
                (object)['id' => 6, 'nama' => 'Es Jeruk Peras', 'kategori' => 'minuman', 'harga' => 7000],
            ]);
        }

        $reviewsPool = [
            1 => ['Makanan kurang enak', 'Pelayanan lambat', 'Kecewa'],
            2 => ['Lumayan tapi agak dingin', 'Kurang meresap bumbunya'],
            3 => ['Cukup enak', 'Standar rasanya', 'Boleh lah'],
            4 => ['Enak dan cepat', 'Porsi pas, rasanya mantap', 'Rekomendasi'],
            5 => ['Sangat enak sekali!', 'Mantap jiwa, pelayanan ramah', 'Juara! Pasti kesini lagi'],
        ];

        for ($day = 0; $day < 30; $day++) {
            $date = Carbon::now()->subDays($day);
            // Increase orders for past week ($day < 7) to have higher volume (+30 or more orders across the week)
            $dailyOrdersCount = ($day < 7) ? rand(10, 15) : rand(6, 10);
            $tableLastOrderTime = [];

            for ($i = 0; $i < $dailyOrdersCount; $i++) {
                $tableId = rand(1, 10);
                $attempts = 0;
                do {
                    $hour = rand(7, 16);
                    $minute = rand(0, 59);
                    $second = rand(0, 59);
                    $createdAt = $date->copy()->setTime($hour, $minute, $second);

                    $valid = true;
                    if (isset($tableLastOrderTime[$tableId])) {
                        foreach ($tableLastOrderTime[$tableId] as $existingTime) {
                            if (abs($createdAt->diffInHours($existingTime)) < 2) {
                                $valid = false;
                                break;
                            }
                        }
                    }
                    $attempts++;
                    if ($attempts > 30) {
                        $tableId = rand(1, 10);
                    }
                } while (!$valid && $attempts < 50);

                if ($createdAt->hour > 17 || ($createdAt->hour == 17 && ($createdAt->minute > 0 || $createdAt->second > 0))) {
                    $createdAt->setTime(17, 0, 0);
                }

                $tableLastOrderTime[$tableId][] = $createdAt;

                $statusDb = 'selesai';
                $statusPembayaran = 'lunas';
                $rating = rand(4, 5);
                $review = $reviewsPool[$rating][array_rand($reviewsPool[$rating])];

                $orderId = DB::table('orders')->insertGetId([
                    'table_id' => $tableId,
                    'status' => $statusDb,
                    'metode_pembayaran' => ['cash', 'transfer', 'qris'][rand(0, 2)],
                    'status_pembayaran' => $statusPembayaran,
                    'rating' => $rating,
                    'review' => $review,
                    'created_at' => $createdAt,
                    'updated_at' => $createdAt,
                ]);

                $numItems = rand(1, 4);
                $selectedProducts = $products->random(min($numItems, $products->count()));
                
                foreach ($selectedProducts as $product) {
                    DB::table('order_items')->insert([
                        'order_id' => $orderId,
                        'product_id' => $product->id,
                        'qty' => rand(1, 3),
                        'kategori_item' => $product->kategori,
                        'status_item' => 'selesai',
                        'created_at' => $createdAt,
                        'updated_at' => $createdAt,
                    ]);
                }
            }
        }
    }
}