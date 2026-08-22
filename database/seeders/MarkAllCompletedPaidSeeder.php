<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MarkAllCompletedPaidSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('orders')
            ->where('status', 'selesai')
            ->update([
                'status_pembayaran' => 'lunas'
            ]);
    }
}