<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TodayOrdersSeeder extends Seeder
{
    public function run(): void
    {
        $statuses = ['diproses', 'selesai', 'menunggu', 'dibatalkan'];
        $products = DB::table('products')->get();
        
        $reviewsPool = [
            1 => ['Makanan kurang enak', 'Pelayanan lambat', 'Kecewa'],
            2 => ['Lumayan tapi agak dingin', 'Kurang meresap bumbunya'],
            3 => ['Cukup enak', 'Standar rasanya', 'Boleh lah'],
            4 => ['Enak dan cepat', 'Porsi pas, rasanya mantap', 'Rekomendasi'],
            5 => ['Sangat enak sekali!', 'Mantap jiwa, pelayanan ramah', 'Juara! Pasti kesini lagi'],
        ];

        for ($i = 0; $i < 10; $i++) {
            $createdAt = Carbon::today()->setTime(8 + $i, rand(0, 59), rand(0, 59));
            $tableId = rand(1, 10);
            $statusDb = $statuses[array_rand($statuses)];

            if ($statusDb === 'selesai') {
                $statusPembayaran = 'lunas';
            } elseif ($statusDb === 'dibatalkan') {
                $statusPembayaran = 'belum_bayar';
            } else {
                $statusPembayaran = ['belum_bayar', 'menunggu_konfirmasi', 'lunas'][rand(0, 2)];
            }

            $rating = null;
            $review = null;
            if ($statusDb !== 'dibatalkan') {
                $rating = rand(3, 5);
                $review = $reviewsPool[$rating][array_rand($reviewsPool[$rating])];
            }

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

            $numItems = rand(1, 3);
            $selectedProducts = $products->random(min($numItems, $products->count()));
            
            foreach ($selectedProducts as $product) {
                $itemStatus = 'menunggu';
                if ($statusDb === 'selesai') $itemStatus = 'selesai';
                if ($statusDb === 'dibatalkan') $itemStatus = 'dibatalkan';
                if ($statusDb === 'diproses') $itemStatus = 'diproses';

                DB::table('order_items')->insert([
                    'order_id' => $orderId,
                    'product_id' => $product->id,
                    'qty' => rand(1, 2),
                    'kategori_item' => $product->kategori,
                    'status_item' => $itemStatus,
                    'created_at' => $createdAt,
                    'updated_at' => $createdAt,
                ]);
            }
        }
    }
}