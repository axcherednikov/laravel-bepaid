<?php

namespace Excent\BePaidLaravel\Enums;

use MyCLabs\Enum\Enum;

class TransactionTypesEnum extends Enum
{
    private const AUTHORIZATION = 'authorization';
    private const PAYMENT = 'payment';
    private const TOKENIZATION = 'tokenization';
}
