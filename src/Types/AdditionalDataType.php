<?php

namespace Excent\BePaidLaravel\Types;

use Excent\BePaidLaravel\Contracts\FillingDTOContract;

class AdditionalDataType implements FillingDTOContract
{
    public $receipt = [];
    public $contract = [];
    public $meta = [];
}
