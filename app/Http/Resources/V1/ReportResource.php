<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class ReportResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'date' => $this->created_at?->toDateString(),
            'driver_name' => $this->driver?->user?->full_name,
            'vehicle' => Str::title($this->driver?->vehicle_make.', '.$this->driver->vehicle_model),
            'delivered' => $this->packages_delivered,
            'returned' => $this->packages_returned,
            'total' => $this->packages_delivered + $this->packages_returned
        ];
    }
}
