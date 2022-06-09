<?php

namespace Excent\BePaidLaravel\Types;

use Excent\BePaidLaravel\Contracts\FillingDTOContract;

class MoneyType implements FillingDTOContract
{
    public $amount;
    public $currency;
    public $cents;
}
