<?php

namespace Excent\BePaidLaravel\Facades;

use Illuminate\Support\Facades\Facade;

class Refund extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'bepaid.refund';
    }
}
