<?php

namespace Excent\BePaidLaravel\Facades;

use Illuminate\Support\Facades\Facade;

class CardToken extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'bepaid.cardToken';
    }
}
