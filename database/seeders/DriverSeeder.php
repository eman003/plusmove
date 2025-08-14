<?php

namespace Database\Seeders;

use App\Models\V1\Driver;
use App\Models\V1\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DriverSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::where('role_id', 2)->get()
            ->each(fn ($user) => Driver::factory()->create(['user_id' => $user->id]))
        ;
    }
}
