<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Order;

class OrdersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Order::truncate();
        for ($i = 0; $i < 30; $i++) {
            Order::factory()
                ->hasAttached(
                    Product::inRandomOrder()->limit(5)->get(),
                    [
                        'quantity' => random_int(1, 10)
                    ]
                )
                ->create();
        }
    }
}
