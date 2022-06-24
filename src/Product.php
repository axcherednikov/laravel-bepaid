<?php

declare(strict_types=1);

namespace Excent\BePaidLaravel;

use BeGateway\Product as BePaidProduct;

class Product extends GatewayAbstract
{
    public function __construct(public BePaidProduct $operation)
    {
        $config = config('bepaid.urls');

        $operation->setNotificationUrl(route($config['notifications']['name'], [], true));
        $operation->setSuccessUrl(route($config['success']['name'], [], true));
        $operation->setFailUrl(route($config['fail']['name'], [], true));
        $operation->setReturnUrl(route($config['return']['name'], [], true));
    }
}
