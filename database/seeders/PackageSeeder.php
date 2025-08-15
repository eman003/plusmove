<?php

namespace Database\Seeders;

use App\Models\V1\Customer;
use App\Models\V1\Delivery;
use App\Models\V1\Package;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Package::factory()->count(rand(10, 50))->create([
            'customer_id' => fake()->randomElement(Customer::all()->pluck('id')->toArray()),
            'delivery_id' => fake()->randomElement([...Delivery::all()->pluck('id')->toArray(), null]),
        ]);
    }
}
