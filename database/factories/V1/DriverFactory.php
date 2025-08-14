<?php

namespace Database\Factories\V1;

use App\Models\V1\City;
use App\Models\V1\User;
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
        $makeModel = $this->getVehicleMakeModel();
        return [
            'user_id' => User::factory()->create()->id,
            //'city_id' => City::factory()->create()->id,
            'driver_license_expires_at' => now()->addYears(rand(1, 5))->toDateTimeString(),
            'vehicle_make' => $makeModel['make']??null,
            'vehicle_model' => $makeModel['model']??null,
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

        $vehicleMake = array_rand($vehicleModelsByMake);

        return [
            'make' => $vehicleMake,
            'model' => $vehicleModelsByMake[$vehicleMake][rand(0, count($vehicleModelsByMake[$vehicleMake]) - 1)],
        ];
    }

    private function generateNumberPlate(): string
    {
        return fake()->unique()->regexify(fake()->randomElement([
            '^[A-Z]{2} [0-9]{2} [A-Z]{2} (GP|LP|MP|NW|FS|NC|EC)$', // e.g., ABC 123 GP
            'ND \d{3}-\d{3}', // KZN Durban
            '(CA|CF|CJ|CK|CL|CN|CS|CT|CY|CW|CZ) \d{3}-\d{3}', // Western Cape prefixes
        ]));
    }
}
