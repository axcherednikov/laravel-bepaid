<?php

declare(strict_types=1);

namespace Excent\BePaidLaravel;

use BeGateway\CardToken as BePaidCardToken;

class CardToken extends GatewayAbstract
{
    public function __construct(public BePaidCardToken $operation)
    {
    }
}
