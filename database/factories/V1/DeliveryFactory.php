<?php

namespace Database\Factories\V1;

use App\Enums\DeliveryStatusEnum;
use App\Models\Driver;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Delivery>
 */
class DeliveryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'driver_id' => fake()->randomElement(
                Driver::factory()->count(10)->create()->pluck('id')->toArray()
            ),
            'status' => fake()->randomElement(DeliveryStatusEnum::cases()),
        ];
    }
}
