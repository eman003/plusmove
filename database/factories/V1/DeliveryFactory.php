<?php

namespace Database\Factories\V1;

use App\Enums\DeliveryStatusEnum;
use App\Models\V1\Driver;
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
        $status = fake()->randomElement(DeliveryStatusEnum::cases())->value;
        return [
            'driver_id' => Driver::factory()->create()->id,
            'status' => $status,
            'delivered_at' => $status === DeliveryStatusEnum::DELIVERED->value ? now()->subHours(rand(1,24)) : null,
            'cancelled_at' => $status === DeliveryStatusEnum::CANCELLED->value ? now()->subHours(rand(1,24)) : null,
            'delivery_note' => $status === DeliveryStatusEnum::FAILED->value ? fake()->sentence() : null,
        ];
    }
}
