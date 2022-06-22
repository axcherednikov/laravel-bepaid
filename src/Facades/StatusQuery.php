<?php

namespace Excent\BePaidLaravel\Facades;

use Illuminate\Support\Facades\Facade;

class StatusQuery extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'bepaid.query';
    }
}
