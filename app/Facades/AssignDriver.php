<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class AssignDriver extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'driver';
    }
}
