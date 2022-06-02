<?php

namespace Excent\BePaidLaravel\Facades;

use Illuminate\Support\Facades\Facade;

class ChildTransaction extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'bepaid.childTransaction';
    }
}
