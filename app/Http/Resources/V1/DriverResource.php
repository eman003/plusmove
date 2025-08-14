<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DriverResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'full_name' => $this->user?->full_name,
            'city' => $this->user?->addresses?->first()?->city,
            'vehicle_make' => $this->vehicle_make,
            'vehicle_model' => $this->vehicle_model,
            'vehicle_colour' => $this->vehicle_colour,
            'vehicle_registration_number' => $this->vehicle_registration_number,
            'driver_license_expires_at' => $this->driver_license_expires_at?->toDateString(),
        ];
    }
}
