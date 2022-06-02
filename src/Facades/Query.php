<?php

namespace Excent\BePaidLaravel\Facades;

use Illuminate\Support\Facades\Facade;

class Query extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'bepaid.query';
    }
}
