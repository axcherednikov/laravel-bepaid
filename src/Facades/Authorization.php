<?php

namespace Excent\BePaidLaravel\Facades;

use Illuminate\Support\Facades\Facade;

class Authorization extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'bepaid.authorization';
    }
}
