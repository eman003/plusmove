<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Address extends Model
{
    /** @use HasFactory<\Database\Factories\AddressFactory> */
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'address_line_1',
        'address_line_2',
        'suburb',
        'city',
        'province',
        'postal_code',
        'country',
        'addressable_id',
        'addressable_type',
    ];

    public function addressable(): MorphTo
    {
        return $this->morphTo();
    }
}
