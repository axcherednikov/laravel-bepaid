<?php

declare(strict_types=1);

namespace Excent\BePaidLaravel;

use BeGateway\PaymentOperation;
use Excent\BePaidLaravel\Contracts\FillingDTOContract;
use Excent\BePaidLaravel\Contracts\IGateway;
use Excent\BePaidLaravel\Dtos\PaymentDto;
use Illuminate\Support\Str;

class Payment extends GatewayAbstract
{
    public function __construct(public PaymentOperation $operation)
    {
        $operation->setNotificationUrl(route(config('bepaid.urls.notifications.name'), [], true));
    }

    public function fill(FillingDTOContract $data, $object = null): IGateway
    {
        if ($data instanceof PaymentDto && empty($data->tracking_id)) {
            $data->tracking_id = Str::uuid()->toString();
        }

        return parent::fill($data, $object);
    }
}
