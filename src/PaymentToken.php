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
        $config = config('bepaid.urls');

        $operation->setNotificationUrl(route($config['notifications']['name'], [], true));
        $operation->setSuccessUrl(route($config['success']['name'], [], true));
        $operation->setDeclineUrl(route($config['decline']['name'], [], true));
        $operation->setFailUrl(route($config['fail']['name'], [], true));
        $operation->setCancelUrl(route($config['cancel']['name'], [], true));
    }

    public function fill(FillingDTOContract $data, $object = null): IGateway
    {
        if ($data instanceof PaymentTokenDto && empty($data->tracking_id)) {
            $data->tracking_id = Str::uuid()->toString();
        }

        return parent::fill($data, $object);
    }
}
