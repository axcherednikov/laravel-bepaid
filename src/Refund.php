<?php

namespace Excent\BePaidLaravel;

use BeGateway\RefundOperation;
use BeGateway\ResponseBase;
use Excent\BePaidLaravel\Contracts\IGateway;
use Excent\BePaidLaravel\Dtos\RefundDto;

class Refund extends GatewayAbstract
{
    public RefundOperation $operation;

    public function __construct(RefundOperation $operation)
    {
        $this->operation = $operation;
    }

    /**
     * @param null $data
     *
     * @return \BeGateway\ResponseBase
     * @throws \Exception
     */
    public function submit($data = null): ResponseBase
    {
        return parent::submit($data);
    }

    /**
     * @param RefundDto                                                                                     $data
     * @param null|\BeGateway\Money|\BeGateway\AdditionalData|\BeGateway\Product|\BeGateway\RefundOperation $object
     *
     * @return \Excent\BePaidLaravel\Contracts\IGateway
     */
    public function fill($data, $object = null): IGateway
    {
        return parent::fill($data, $object);
    }
}
