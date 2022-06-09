<?php

declare(strict_types=1);

namespace Excent\BePaidLaravel;

use BeGateway\ResponseBase;
use Excent\BePaidLaravel\Contracts\FillingDTOContract;
use Illuminate\Support\Str;
use Excent\BePaidLaravel\Contracts\IGateway;
use Excent\BePaidLaravel\Exceptions\BadRequestException;

abstract class GatewayAbstract implements IGateway
{
    public function submit(FillingDTOContract $data = null): ResponseBase
    {
        if ($data) {
            $this->fill($data);
        }

        $responseBase = $this->operation->submit();

        if ($responseBase->isError()) {
            $response = $responseBase->getResponse()->response;

            throw new BadRequestException($response->message, $response->errors);
        }

        return $responseBase;
    }

    public function fill(FillingDTOContract $data, $object = null): IGateway
    {
        $obj = $object ?? $this->operation;

        foreach ($data as $property => $value) {
            if ($value !== null) {
                if (is_object($value)) {
                    $snakeCaseProp = Str::snake($property);

                    $this->fill($value, $obj->{$snakeCaseProp});
                } else {
                    $formattedProperty = strtolower(str_replace('_', '', $property));

                    $method = sprintf("set%s", $formattedProperty);

                    if (method_exists($obj, $method)) {
                        $obj->{$method}($value);
                    }
                }
            }
        }

        return $this;
    }
}
