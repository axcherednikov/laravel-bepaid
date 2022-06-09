<?php

declare(strict_types=1);

namespace Excent\BePaidLaravel;

use BeGateway\CreditOperation;
use Illuminate\Support\Str;
use Excent\BePaidLaravel\Contracts\IGateway;
use Excent\BePaidLaravel\Dtos\CreditDto;

class Credit extends GatewayAbstract
{
    public function __construct(public CreditOperation $operation)
    {
    }

    public function fill($data, $object = null): IGateway
    {
        if ($data instanceof CreditDto && empty($data->tracking_id)) {
            $data->tracking_id = Str::uuid()->toString();
        }

        return parent::fill($data, $object);
    }
}
