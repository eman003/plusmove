<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class PackageResource extends JsonResource
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
            'customer' => $this->customer?->name,
            'status' => $this->status,
            'tracking_number' => $this->tracking_number,
            'delivery_note' => $this->delivery_note,
            'delivered_at' => $this->delivered_at?->toDateString(),
            'cancelled_at' => $this->cancelled_at?->toDateString(),
            'delivery_address' => $this->address,
        ];
    }
}
