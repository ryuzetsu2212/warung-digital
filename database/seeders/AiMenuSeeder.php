<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class AiMenuSeeder extends Seeder
{
    public function run(): void
    {
        $base = 'https://pbaqettpfiqpxsccuiox.supabase.co/storage/v1/object/public/menu-images/';
        $items = [
            ['nama' => 'Ayam Geprek Sambal', 'kategori' => 'makanan', 'harga' => 22000, 'file' => 'ayam_geprek_with_red_chili_sambal_and_steamed_rice_10.webp'],
            ['nama' => 'Cappuccino', 'kategori' => 'minuman', 'harga' => 15000, 'file' => 'cappuccino_with_latte_art_2.webp'],
            ['nama' => 'Nasi Goreng Ayam', 'kategori' => 'makanan', 'harga' => 25000, 'file' => 'chicken_fried_rice_with_sunny_side_up_egg_and_crackers_12.webp'],
            ['nama' => 'Soto Ayam', 'kategori' => 'makanan', 'harga' => 20000, 'file' => 'chicken_soup_with_shredded_chicken__noodles_and_boiled_egg_8.webp'],
            ['nama' => 'Pisang Goreng Cokelat Keju', 'kategori' => 'makanan', 'harga' => 15000, 'file' => 'crispy_fried_bananas_with_chocolate_and_grated_cheese_6.webp'],
            ['nama' => 'Es Campur', 'kategori' => 'minuman', 'harga' => 18000, 'file' => 'es_campur_2.webp'],
            ['nama' => 'Gado-Gado', 'kategori' => 'makanan', 'harga' => 18000, 'file' => 'gado_gado_5.webp'],
            ['nama' => 'Sate Ayam', 'kategori' => 'makanan', 'harga' => 30000, 'file' => 'grilled_chicken_satay_with_peanut_sauce_and_rice_cakes_11.webp'],
            ['nama' => 'Es Matcha Latte', 'kategori' => 'minuman', 'harga' => 18000, 'file' => 'iced_matcha_latte_with_milk_layers_1.webp'],
            ['nama' => 'Es Kopi Susu', 'kategori' => 'minuman', 'harga' => 18000, 'file' => 'iced_milk_coffee_in_a_clear_glass_3.webp'],
            ['nama' => 'Es Jeruk', 'kategori' => 'minuman', 'harga' => 10000, 'file' => 'iced_orange_drink_with_ice_cubes_4.webp'],
            ['nama' => 'Es Teh Manis', 'kategori' => 'minuman', 'harga' => 7000, 'file' => 'iced_sweet_tea_with_lemon_slices_5.webp'],
            ['nama' => 'Ikan Bakar Sambal Matah', 'kategori' => 'makanan', 'harga' => 32000, 'file' => 'ikan_bakar_sambal_matah_6.webp'],
            ['nama' => 'Mango Smoothie', 'kategori' => 'minuman', 'harga' => 18000, 'file' => 'mango_smoothie_1.webp'],
            ['nama' => 'Mie Goreng Ayam', 'kategori' => 'makanan', 'harga' => 22000, 'file' => 'mie_goreng_with_chicken__egg_and_vegetables_9.webp'],
            ['nama' => 'Nasi Ayam Kremes', 'kategori' => 'makanan', 'harga' => 26000, 'file' => 'nasi_ayam_kremes_8.webp'],
            ['nama' => 'Rendang Sapi', 'kategori' => 'makanan', 'harga' => 35000, 'file' => 'rendang_sapi_7.webp'],
            ['nama' => 'Tahu Crispy', 'kategori' => 'makanan', 'harga' => 12000, 'file' => 'tahu_crispy_4.webp'],
            ['nama' => 'Tempe Mendoan', 'kategori' => 'makanan', 'harga' => 12000, 'file' => 'tempe_mendoan_3.webp'],
        ];

        // Replace existing product rows so old demo products are not shown.
        // Reusing rows preserves foreign keys from historical order_items.
        $existing = Product::orderBy('id')->get();
        foreach ($items as $index => $item) {
            $data = [
                'nama' => $item['nama'],
                'kategori' => $item['kategori'],
                'harga' => $item['harga'],
                'image_url' => $base . $item['file'],
                'is_available' => true,
            ];
            if (isset($existing[$index])) {
                $existing[$index]->update($data);
            } else {
                Product::create($data);
            }
        }

        // Remove extra legacy rows when safe; preserve rows referenced by history.
        $extras = $existing->slice(count($items));
        foreach ($extras as $product) {
            $used = \Illuminate\Support\Facades\Schema::hasTable('order_items')
                && \Illuminate\Support\Facades\DB::table('order_items')
                    ->where('product_id', $product->id)->exists();
            if ($used) {
                $product->update(['is_available' => false, 'image_url' => null]);
            } else {
                $product->delete();
            }
        }
    }
}
