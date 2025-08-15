<?php

namespace Database\Seeders;

use App\Models\V1\Customer;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Customer::factory()
        ->count(rand(10, 50))
        ->has(\App\Models\V1\Address::factory()->count(rand(1, 3)), 'addresses')
        ->create();
    }
}
