<?php

namespace Database\Seeders;

use App\Enums\DeliveryStatusEnum;
use App\Facades\AssignDriver;
use App\Models\V1\Customer;
use App\Models\V1\Driver;
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
        $customers = Customer::with('addresses')->get();

        Package::factory()
            ->count(rand(100, 300))
            ->state(function () use ($customers) {
                $customer = $customers->random();

                return [
                    'customer_id' => $customer->id,
                    'address_id' => $customer?->addresses?->first()?->id,
                    'driver_id' => AssignDriver::getDriverWithLeastPackages()?->id,
                    'status' => $status = fake()->randomElement(DeliveryStatusEnum::cases())->value,
                    'delivered_at' => $delicery = $status === DeliveryStatusEnum::DELIVERED->value
                        ? now()->subDays(rand(0, 3))
                        : ($status === DeliveryStatusEnum::RETURNED->value ? now()->subDays(rand(1, 10)) : null),
                    'scheduled_for' => $delicery ?? now()->addDays(rand(0, 3))->toDateTimeString(),
                ];
            })
            ->create();

    }
}
