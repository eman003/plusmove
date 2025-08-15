<?php

namespace Database\Seeders;

use App\Enums\DeliveryStatusEnum;
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
        $customers = Customer::with('addresses')->get();

        Package::factory()
            ->count(rand(10, 50))
            ->state(function () use ($customers) {
                $customer = $customers->random();

                return [
                    'customer_id'  => $customer->id,
                    'address_id'   => optional($customer->addresses->random())->id,
                    'delivery_id'  => null,
                    'status'       => $status = fake()->randomElement(DeliveryStatusEnum::cases())->value,
                    'cancelled_at' => $status === DeliveryStatusEnum::CANCELLED->value
                        ? now()->subHours(rand(1, 24))
                        : null,
                    'delivered_at' => $status === DeliveryStatusEnum::DELIVERED->value
                        ? now()->subHours(rand(1, 24))
                        : null,
                ];
            })
            ->create();

    }
}
