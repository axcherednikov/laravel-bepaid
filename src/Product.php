<?php

declare(strict_types=1);

namespace Excent\BePaidLaravel;

use BeGateway\Product as BePaidProduct;

class Product extends GatewayAbstract
{
    public function __construct(public BePaidProduct $operation)
    {
    }
}
