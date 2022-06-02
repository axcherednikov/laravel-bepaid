<?php

namespace Excent\BePaidLaravel;

use BeGateway\CardToken as BePaidCardToken;
use BeGateway\ResponseBase;
use Excent\BePaidLaravel\Contracts\IGateway;
use Excent\BePaidLaravel\Dtos\CardTokenDto;

class CardToken extends GatewayAbstract
{
    public BePaidCardToken $operation;

    public function __construct(BePaidCardToken $token)
    {
        $this->operation = $token;
    }

    /**
     * @param CardTokenDto $data
     *
     * @return \BeGateway\ResponseCardToken
     * @throws \Exception
     */
    public function submit($data = null): ResponseBase
    {
        return parent::submit($data);
    }

    /**
     * @param CardTokenDto                                                                                   $data
     * @param null|\BeGateway\Money|\BeGateway\AdditionalData|\BeGateway\Customer|\BeGateway\GetPaymentToken $object
     *
     * @return \Excent\BePaidLaravel\Contracts\IGateway
     */
    public function fill($data, $object = null): IGateway
    {
        return parent::fill($data, $object);
    }
}
