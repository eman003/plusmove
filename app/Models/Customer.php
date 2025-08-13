<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    /** @use HasFactory<\Database\Factories\CustomerFactory> */
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'first_name',
        'last_name',
        'phone_number',
        'email',
    ];

    public function addresses()
    {
        return $this->morphMany(Address::class, 'addressable');
    }

    public function packages(): HasMany
    {
        return $this->hasMany(Package::class);
    }
}
