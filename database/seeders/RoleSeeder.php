<?php

namespace Database\Seeders;

use App\Models\Role;
use App\StatusEnum;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::insert([
            [
                'name' => 'Admin',
                'description' => 'It gives you access to all the features of the application.',
                'status_id' => StatusEnum::ACTIVE,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Driver',
                'description' => 'It gives you access to view assigned deliveries, update status.',
                'status_id' => StatusEnum::ACTIVE,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Customer',
                'description' => 'It gives you access to view your own packages status.',
                'status_id' => StatusEnum::INACTIVE,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
