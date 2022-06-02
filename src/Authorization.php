<?php

namespace Excent\BePaidLaravel;

use BeGateway\AuthorizationOperation;
use BeGateway\PaymentOperation;
use BeGateway\ResponseBase;
use Illuminate\Support\Str;
use Excent\BePaidLaravel\Contracts\IGateway;
use Excent\BePaidLaravel\Dtos\{AuthorizationDto, PaymentDto};

class Authorization extends GatewayAbstract
{
    /** @var AuthorizationOperation|PaymentOperation */
    public $operation;

    public function __construct(AuthorizationOperation $operation)
    {
        $this->operation = $operation;
    }

    /**
     * @param AuthorizationDto|PaymentDto $data
     *
     * @return \BeGateway\Response
     * @throws \Exception
     */
    public function submit($data = null): ResponseBase
    {
        return parent::submit($data);
    }

    /**
     * @param AuthorizationDto|PaymentDto                                                                    $data
     * @param null|\BeGateway\Money|\BeGateway\AdditionalData|\BeGateway\Customer|\BeGateway\GetPaymentToken $object
     *
     * @return \Excent\BePaidLaravel\Contracts\IGateway
     */
    public function fill($data, $object = null): IGateway
    {
        if ($data instanceof AuthorizationDto && empty($data->tracking_id)) {
            $data->tracking_id = Str::uuid()->toString();
        }

        return parent::fill($data, $object);
    }
}
