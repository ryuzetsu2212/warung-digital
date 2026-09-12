<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DemoOrderSeeder extends Seeder
{
    public function run(): void
    {
        if (DB::table('orders')->exists()) {
            $this->command->info('Orders sudah berisi data; dummy order dilewati.');
            return;
        }

        $tables = DB::table('tables')->pluck('id');
        $products = DB::table('products')->get();
        if ($tables->isEmpty() || $products->isEmpty()) {
            $this->command->warn('Meja atau menu belum tersedia; dummy order dilewati.');
            return;
        }

        for ($i = 0; $i < 100; $i++) {
            $created = Carbon::now()->subDays($i % 30)->setTime(8 + ($i % 12), ($i * 7) % 60, 0);
            $status = $i < 8 ? ['menunggu', 'diproses'][$i % 2] : 'selesai';
            $payment = $status === 'selesai' ? 'lunas' : 'belum_bayar';
            $orderId = DB::table('orders')->insertGetId([
                'table_id' => $tables[$i % $tables->count()],
                'status' => $status,
                'metode_pembayaran' => ['cash', 'transfer', 'qris'][$i % 3],
                'status_pembayaran' => $payment,
                'rating' => $status === 'selesai' ? 4 + ($i % 2) : null,
                'review' => $status === 'selesai' ? 'Pesanan dummy untuk pengujian laporan.' : null,
                'created_at' => $created,
                'updated_at' => $created,
            ]);

            $first = $products[$i % $products->count()];
            $second = $products[($i + 3) % $products->count()];
            foreach ([$first, $second] as $product) {
                $itemStatus = $status === 'selesai' ? 'selesai' : $status;
                DB::table('order_items')->insert([
                    'order_id' => $orderId,
                    'product_id' => $product->id,
                    'qty' => 1 + ($i % 3),
                    'kategori_item' => $product->kategori,
                    'status_item' => $itemStatus,
                    'created_at' => $created,
                    'updated_at' => $created,
                ]);
            }
        }

        $this->command->info('100 dummy order dan 200 order item berhasil dibuat.');
    }
}
