<?php

namespace App\Models\V1;

use App\Enums\DeliveryStatusEnum;
use App\Events\PackageDeliveryStatus;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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
        'cancelled_at',
    ];

    protected static function booted()
    {
        static::creating(function ($package) {
            $package->tracking_number = Str::uuid();
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
            'cancelled_at' => 'datetime',
        ];
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function delivery()
    {
        return $this->belongsTo(Delivery::class);
    }

    public function address()
    {
        return $this->belongsTo(Address::class);
    }
}
