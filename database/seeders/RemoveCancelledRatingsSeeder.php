<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RemoveCancelledRatingsSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('orders')
            ->where('status', 'dibatalkan')
            ->update([
                'rating' => null,
                'review' => null,
            ]);
    }
}