<?php

namespace Database\Factories\V1;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Address>
 */
class AddressFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        fake()->addProvider(new \App\Faker\AddressProvider(fake()));
        return [
            'name' => fake()->randomElement(['Home', 'Work', 'School']),
            'address_line_1' => fake()->complex(),
            'address_line_2' => fake()->addressLine1(),
            'suburb' => fake()->suburb(),
            'city' => fake()->citySA(),
            'province' => fake()->province(),
            'postal_code' => fake()->postcode(),
            'country' => 'South Africa' /*fake()->country()*/,
        ];
    }
}
