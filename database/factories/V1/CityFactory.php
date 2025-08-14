<?php

namespace Database\Factories\V1;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\City>
 */
class CityFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->city(),
            'province' => fake()->randomElement([
                'Limpopo', 'Gauteng', 'Mpumalanga', 'Kwazulu Natal', 'North West', 'Northern Cape',
                'Eastern Cape', 'Western Cape', 'Free State'
            ]),
        ];
    }
}
