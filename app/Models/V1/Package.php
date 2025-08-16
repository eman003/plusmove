<?php

namespace App\Models\V1;

use App\Enums\DeliveryStatusEnum;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Package extends Model
{
    /** @use HasFactory<\Database\Factories\PackageFactory> */
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'customer_id',
        'delivery_id',
        'address_id',
        'status',
        'delivery_note',
        'delivered_at',
        'scheduled_for',
    ];

    protected static function booted(): void
    {
        static::creating(function ($package) {
            $package->tracking_number = Str::uuid();
            //$package->status = DeliveryStatusEnum::NEW;
        });
    }

    public function trackingNumber(): Attribute
    {
        return Attribute::make(
            get: fn(string $value) => Str::upper($value)
        );
    }

    protected function casts(): array
    {
        return [
            'status' => DeliveryStatusEnum::class,
            'delivered_at' => 'datetime',
            'scheduled_for' => 'datetime',
        ];
    }

    public function driver(): BelongsTo
    {
        return $this->belongsTo(Driver::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function address(): BelongsTo
    {
        return $this->belongsTo(Address::class);
    }
}
