<?php

namespace App\Models;

use App\StatusEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    use SoftDeletes;

    protected function casts(): array
    {
        return [
            'status' => StatusEnum::class,
        ];
    }
}
