<?php

namespace Excent\BePaidLaravel\Enums;

use MyCLabs\Enum\Enum;

class PaymentTypesEnum extends Enum
{
    private const CREDIT_CARD = 'credit_card';
    private const ERIP = 'erip';
}
