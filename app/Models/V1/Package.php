<?php

namespace App\Models\V1;

use App\Enums\DeliveryStatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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
    ];

    protected function casts(): array
    {
        return [
            'status' => DeliveryStatusEnum::class,
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
