<?php

namespace Excent\BePaidLaravel\Facades;

use Illuminate\Support\Facades\Facade;

class Webhook extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'bepaid.webhook';
    }
}
