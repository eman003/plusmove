<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Driver>
 */
class DriverFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory()->create()->id,
            'city_id' => City::factory()->create()->id,
            'driver_license_expiry_date' => now()->addYears(rand(1, 5))->toDateTimeString(),
            'vehicle_make' => $this->getVehicleMakeModel()['make']??null,
            'vehicle_model' => $this->getVehicleMakeModel()['model']??null,
            'vehicle_colour' => fake()->colorName(),
            'vehicle_registration_number' => $this->generateNumberPlate(),
        ];
    }

    private function getVehicleMakeModel(): array
    {
        $vehicleModelsByMake = [
            'Volkswagen' => ['Caddy Panel Van', 'Transporter Panel Van'],
            'Toyota' => ['Hilux Single Cab', 'Quantum Panel Van', 'HiAce'],
            'Ford' => ['Ranger Single Cab', 'Transit Connect', 'Transit Custom'],
            'Nissan' => ['NP200', 'NV200'],
            'Isuzu' => ['D-Max Single Cab'],
            'Hyundai' => ['H-100', 'H1 Panel Van', 'Staria Panel Van'],
            'Kia'  => ['K2700', 'K2500'],
            'Renault' => ['Kangoo Express', 'Trafic Panel Van'],
            'Peugeot' => ['Partner', 'Expert'],
            'Citroën' => ['Berlingo'],
            'Mahindra' => ['Bolero Maxitruck Plus', 'Pik Up Single Cab'],
            'Suzuki' => ['Super Carry'],
            'Mercedes-Benz' => ['Vito Panel Van', 'Sprinter Panel Van'],
            'Opel' => ['Combo Cargo'],
        ];

        $vehicleMake = fake()->randomElement(array_keys($vehicleModelsByMake));

        return [
            'make' => $vehicleMake,
            'model' => fake()->randomElement($vehicleModelsByMake[$vehicleMake])
        ];
    }

    private function generateNumberPlate(): string
    {
        return fake()->unique()->regexify(fake()->randomElement([
            '[A-Z]{3} ?\d{3} ?(GP|L|MP|NW|FS|NC|EC)', // e.g., ABC 123 GP
            'ND \d{3}-\d{3}', // KZN Durban
            '(CA|CF|CJ|CK|CL|CN|CS|CT|CY|CW|CZ) \d{3}-\d{3}', // Western Cape prefixes
        ]));
    }
}
