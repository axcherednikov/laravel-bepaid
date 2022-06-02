<?php

namespace Excent\BePaidLaravel\Facades;

use Illuminate\Support\Facades\Facade;

class Product extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'bepaid.product';
    }
}
