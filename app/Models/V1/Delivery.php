<?php

namespace App\Models\V1;

use App\Enums\DeliveryStatusEnum;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Delivery extends Model
{
    /** @use HasFactory<\Database\Factories\DeliveryFactory> */
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'driver_id',
        'status',
        'delivered_at',
        'cancelled_at',
        'delivery_note',
        'tracking_number'
    ];

    protected function casts(): array
    {
        return [
            'delivered_at' => 'datetime',
            'cancelled_at' => 'datetime',
            'status' => DeliveryStatusEnum::class,
        ];
    }

    protected static function booted()
    {
        static::creating(function ($delivery) {
            $delivery->tracking_number = Str::uuid();
        });
    }

    public function trackingNumber(): Attribute
    {
        return Attribute::make(
            get: fn(string $value) => Str::upper($value)
        );
    }

    public function driver(): BelongsTo
    {
        return $this->belongsTo(Driver::class);
    }

    public function packages(): HasMany
    {
        return $this->hasMany(Package::class);
    }
}
