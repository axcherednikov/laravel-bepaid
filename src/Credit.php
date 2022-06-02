<?php

namespace Excent\BePaidLaravel;

use BeGateway\CreditOperation;
use BeGateway\ResponseBase;
use Illuminate\Support\Str;
use Excent\BePaidLaravel\Contracts\IGateway;
use Excent\BePaidLaravel\Dtos\CreditDto;

class Credit extends GatewayAbstract
{
    public CreditOperation $operation;

    public function __construct(CreditOperation $operation)
    {
        $this->operation = $operation;
    }

    /**
     * @param null|CreditDto $data
     *
     * @return \BeGateway\Response
     * @throws \Exception
     */
    public function submit($data = null): ResponseBase
    {
        return parent::submit($data);
    }

    /**
     * @param CreditDto $data
     *
     * @param null      $object
     *
     * @return IGateway
     */
    public function fill($data, $object = null): IGateway
    {
        if ($data instanceof CreditDto && empty($data->tracking_id)) {
            $data->tracking_id = Str::uuid()->toString();
        }

        return parent::fill($data, $object);
    }
}
