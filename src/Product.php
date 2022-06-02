<?php

namespace Excent\BePaidLaravel;

use BeGateway\AdditionalData;
use BeGateway\Money;
use BeGateway\Product as BePaidProduct;
use BeGateway\ResponseApiProduct;
use BeGateway\ResponseBase;
use Excent\BePaidLaravel\Contracts\IGateway;
use Excent\BePaidLaravel\Dtos\ProductDto;
use Exception;

class Product extends GatewayAbstract
{
    public BePaidProduct $operation;

    public function __construct(BePaidProduct $operation)
    {
        $this->operation = $operation;
    }

    /**
     * @param ProductDto $data
     *
     * @return ResponseApiProduct
     * @throws Exception
     */
    public function submit($data = null): ResponseBase
    {
        return parent::submit($data);
    }

    /**
     * @param ProductDto                               $data
     * @param null|Money|AdditionalData|BePaidProduct  $object
     *
     * @return IGateway
     */
    public function fill($data, $object = null): IGateway
    {
        return parent::fill($data, $object);
    }
}
