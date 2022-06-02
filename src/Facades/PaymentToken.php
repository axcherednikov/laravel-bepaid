<?php

namespace Excent\BePaidLaravel\Facades;

use Illuminate\Support\Facades\Facade;

class PaymentToken extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'bepaid.paymentToken';
    }
}
