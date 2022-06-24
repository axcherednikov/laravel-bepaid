<?php

declare(strict_types=1);

namespace Excent\BePaidLaravel;

use BeGateway\AuthorizationOperation;
use Illuminate\Support\Str;
use Excent\BePaidLaravel\Contracts\IGateway;
use Excent\BePaidLaravel\Contracts\FillingDTOContract;
use Excent\BePaidLaravel\Dtos\AuthorizationDto;

class Authorization extends GatewayAbstract
{
    public function __construct(public AuthorizationOperation $operation)
    {
    }

    public function fill(FillingDTOContract $data, $object = null): IGateway
    {
        $config = config('bepaid.urls');

        $this->operation->setNotificationUrl(route($config['notifications']['name'], [], true));
        $this->operation->setReturnUrl(route($config['return']['name'], [], true));

        if ($data instanceof AuthorizationDto && empty($data->tracking_id)) {
            $data->tracking_id = Str::uuid()->toString();
        }

        return parent::fill($data, $object);
    }
}
