<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;

class ShiftOrdersSeeder extends Seeder
{
    public function run(): void
    {
        $orders = Order::whereDate('created_at', today())->get();
        foreach ($orders as $o) {
            $newDate = now()->subDays(rand(2, 25));
            $o->timestamps = false;
            $o->created_at = $newDate;
            $o->updated_at = $newDate;
            $o->save();

            foreach ($o->orderItems as $item) {
                $item->timestamps = false;
                $item->created_at = $newDate;
                $item->updated_at = $newDate;
                $item->save();
            }
        }
    }
}