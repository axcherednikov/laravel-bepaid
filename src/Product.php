<?php

declare(strict_types=1);

namespace Excent\BePaidLaravel;

use BeGateway\Product as BePaidProduct;
use Excent\BePaidLaravel\Contracts\FillingDTOContract;
use Excent\BePaidLaravel\Contracts\IGateway;

class Product extends GatewayAbstract
{
    public function __construct(public BePaidProduct $operation)
    {
    }

    public function fill(FillingDTOContract $data, $object = null): IGateway
    {
        $config = config('bepaid.urls');

        $this->operation->setNotificationUrl(route($config['notifications']['name'], [], true));
        $this->operation->setSuccessUrl(route($config['success']['name'], [], true));
        $this->operation->setFailUrl(route($config['fail']['name'], [], true));
        $this->operation->setReturnUrl(route($config['return']['name'], [], true));

        return parent::fill($data, $object);
    }
}
