<?php

namespace Database\Factories\V1;

use App\Enums\DeliveryStatusEnum;

use App\Models\V1\Customer;
use App\Models\V1\Delivery;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Package>
 */
class PackageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $customer = Customer::factory()->create();
        $delivery = Delivery::factory()->create();
        $status = $delivery->status ?? DeliveryStatusEnum::NEW->value;
        return [
            'customer_id' => $customer->id,
            'delivery_id' => $delivery->id,
            'address_id' => $customer->addresses?->first()?->id,
            'status' => $delivery->status ?? DeliveryStatusEnum::NEW->value,
            'delivery_note' => fake()->sentence(),
            'delivered_at' => $status === DeliveryStatusEnum::DELIVERED->value ? now()->subHours(rand(1,24)) : null,
        ];
    }
}
