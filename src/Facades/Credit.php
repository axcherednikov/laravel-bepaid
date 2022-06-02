<?php

namespace Excent\BePaidLaravel\Facades;

use Illuminate\Support\Facades\Facade;

class Credit extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'bepaid.credit';
    }
}
