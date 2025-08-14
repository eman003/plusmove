<?php

namespace Database\Seeders;

use App\Models\V1\Role;
use App\Models\V1\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()
            ->count(rand(10, 50))
            ->has(\App\Models\V1\Address::factory()->count(rand(1, 3)), 'addresses')
            ->create();
    }
}
