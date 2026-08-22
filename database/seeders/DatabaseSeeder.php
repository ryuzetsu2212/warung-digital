<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Table;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Buat 10 meja (tambah 5 meja baru)
        for ($i = 1; $i <= 10; $i++) {
            Table::firstOrCreate(
                ['nomor_meja' => $i],
                ['uuid' => (string) Str::uuid()]
            );
        }

        $products = [
            // MAKANAN
            [
                'nama' => 'Sate Ayam Madura',
                'kategori' => 'makanan',
                'harga' => 30000,
                'image_url' => 'https://images.unsplash.com/photo-1555939594-58d7cb561ad1?auto=format&fit=crop&w=800&q=80',
                'is_available' => true,
            ],
            [
                'nama' => 'Ayam Geprek Sambal Matah',
                'kategori' => 'makanan',
                'harga' => 22000,
                'image_url' => 'https://images.unsplash.com/photo-1562967914-608f82629710?auto=format&fit=crop&w=800&q=80',
                'is_available' => true,
            ],
            [
                'nama' => 'Nasi Rawon Daging Sapi',
                'kategori' => 'makanan',
                'harga' => 28000,
                'image_url' => 'https://images.unsplash.com/photo-1543339308-43e59d6b73a6?auto=format&fit=crop&w=800&q=80',
                'is_available' => true,
            ],
            [
                'nama' => 'Bakso Urat Sapi',
                'kategori' => 'makanan',
                'harga' => 18000,
                'image_url' => 'https://images.unsplash.com/photo-1687425973283-d0d266b73325?q=80&w=870&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
                'is_available' => true,
            ],
            [
                'nama' => 'Soto Ayam Lamongan',
                'kategori' => 'makanan',
                'harga' => 20000,
                'image_url' => 'https://images.unsplash.com/photo-1681378128359-a5c2492a3535?q=80&w=387&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
                'is_available' => false,
            ],
            [
                'nama' => 'Indomie Goreng',
                'kategori' => 'makanan',
                'harga' => 10000,
                'image_url' => 'https://images.unsplash.com/photo-1730177871173-94898b9a17ec?q=80&w=870&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
                'is_available' => true,
            ],
            [
                'nama' => 'Indomie Rebus',
                'kategori' => 'makanan',
                'harga' => 10000,
                'image_url' => 'https://images.unsplash.com/photo-1569718212165-3a8278d5f624?auto=format&fit=crop&w=800&q=80',
                'is_available' => true,
            ],

            // MINUMAN
            [
                'nama' => 'Es Teh Manis',
                'kategori' => 'minuman',
                'harga' => 5000,
                'image_url' => 'https://images.unsplash.com/photo-1556679343-c7306c1976bc?auto=format&fit=crop&w=800&q=80',
                'is_available' => true,
            ],
            [
                'nama' => 'Es Jeruk Peras',
                'kategori' => 'minuman',
                'harga' => 7000,
                'image_url' => 'https://images.unsplash.com/photo-1613478223719-2ab802602423?auto=format&fit=crop&w=800&q=80',
                'is_available' => true,
            ],
            [
                'nama' => 'Kopi Hitam Robusta',
                'kategori' => 'minuman',
                'harga' => 10000,
                'image_url' => 'https://images.unsplash.com/photo-1514432324607-a09d9b4aefdd?auto=format&fit=crop&w=800&q=80',
                'is_available' => true,
            ],
            [
                'nama' => 'Jus Buah',
                'kategori' => 'minuman',
                'harga' => 15000,
                'image_url' => 'https://images.unsplash.com/photo-1551024709-8f23befc6f87?auto=format&fit=crop&w=800&q=80',
                'is_available' => true,
            ],
            [
                'nama' => 'Es Kelapa Muda Segar',
                'kategori' => 'minuman',
                'harga' => 12000,
                'image_url' => 'https://images.unsplash.com/photo-1617611140379-0e0ec17cc45f?q=80&w=870&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
                'is_available' => true,
            ],
            [
                'nama' => 'Es Krim',
                'kategori' => 'minuman',
                'harga' => 15000,
                'image_url' => 'https://images.unsplash.com/photo-1563805042-7684c019e1cb?auto=format&fit=crop&w=800&q=80',
                'is_available' => false,
            ],
            [
                'nama' => 'Cappucino',
                'kategori' => 'minuman',
                'harga' => 15000,
                'image_url' => 'https://plus.unsplash.com/premium_photo-1674327105074-46dd8319164b?q=80&w=870&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
                'is_available' => true,
            ],
            [
                'nama' => 'Milo',
                'kategori' => 'minuman',
                'harga' => 12000,
                'image_url' => 'https://images.unsplash.com/photo-1544787219-7f47ccb76574?auto=format&fit=crop&w=800&q=80',
                'is_available' => true,
            ],
        ];

        foreach ($products as $product) {
            Product::updateOrCreate(
                ['nama' => $product['nama']],
                [
                    'image_url'    => $product['image_url'],
                    'harga'        => $product['harga'],
                    'kategori'     => $product['kategori'],
                    'is_available' => $product['is_available'],
                ]
            );
        }

        $this->call(OrderSeeder::class);
    }
}