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
        $config = config('bepaid.urls');

        $this->operation->setNotificationUrl(route($config['notifications']['name'], [], true));
        $this->operation->setSuccessUrl(route($config['success']['name'], [], true));
        $this->operation->setDeclineUrl(route($config['decline']['name'], [], true));
        $this->operation->setFailUrl(route($config['fail']['name'], [], true));
        $this->operation->setCancelUrl(route($config['cancel']['name'], [], true));

        if ($data instanceof PaymentTokenDto && empty($data->tracking_id)) {
            $data->tracking_id = Str::uuid()->toString();
        }

        return parent::fill($data, $object);
    }
}
