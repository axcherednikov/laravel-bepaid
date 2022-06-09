<?php

declare(strict_types=1);

namespace Excent\BePaidLaravel;

use BeGateway\RefundOperation;

class Refund extends GatewayAbstract
{
    public function __construct(public RefundOperation $operation)
    {
    }
}
