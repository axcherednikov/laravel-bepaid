<?php

declare(strict_types=1);

namespace Excent\BePaidLaravel;

use BeGateway\GetPaymentToken;
use Excent\BePaidLaravel\Contracts\FillingDTOContract;
use Illuminate\Support\Str;
use Excent\BePaidLaravel\Contracts\IGateway;
use Excent\BePaidLaravel\Dtos\PaymentTokenDto;

class PaymentToken extends GatewayAbstract
{
    public function __construct(public GetPaymentToken $operation)
    {
    }

    public function fill(FillingDTOContract $data, $object = null): IGateway
    {
        if ($data instanceof PaymentTokenDto && empty($data->tracking_id)) {
            $data->tracking_id = Str::uuid()->toString();
        }

        return parent::fill($data, $object);
    }
}
