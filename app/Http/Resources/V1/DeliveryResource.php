<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class DeliveryResource extends JsonResource
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
            'driver' => $this->driver?->user?->full_name,
            'status' => Str::of($this->status?->name)->replace('_', ' ')->title(),
            'delivery_note' => $this->delivery_note,
            'tracking_number' => $this->tracking_number,
            'delivered_at' => $this->delivered_at?->toDateTimeString(),
            'cancelled_at' => $this->cancelled_at?->toDateTimeString(),
            'packages' => $this->packages
        ];
    }
}
