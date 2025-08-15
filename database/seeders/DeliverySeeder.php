<?php

namespace Database\Seeders;

use App\Models\V1\Delivery;
use App\Models\V1\Driver;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DeliverySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $drivers = Driver::all();
        Delivery::factory()
            ->count(rand(10, 50))
            ->state(fn () => ['driver_id' => $drivers->random()->id])
            ->create();
    }
}
