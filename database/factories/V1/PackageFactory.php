<?php

namespace Database\Factories\V1;

use App\Enums\DeliveryStatusEnum;
use App\Models\Customer;
use App\Models\Delivery;
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
        return [
            'customer_id' => Customer::factory()->create()->id,
            'delivery_id' => Delivery::factory()->create()->id,
            'status' => DeliveryStatusEnum::NEW->value,
            'delivery_note' => fake()->sentence(),
        ];
    }
}
