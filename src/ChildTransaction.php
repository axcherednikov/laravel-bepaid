<?php

declare(strict_types=1);

namespace Excent\BePaidLaravel;

use BeGateway\CaptureOperation;
use BeGateway\VoidOperation;
use Excent\BePaidLaravel\Contracts\FillingDTOContract;
use Excent\BePaidLaravel\Contracts\IGateway;
use Excent\BePaidLaravel\Dtos\{CaptureDto, VoidDto};

class ChildTransaction extends GatewayAbstract
{
    public VoidOperation|CaptureOperation $operation;

    public function __construct(
        private CaptureOperation $capture,
        private VoidOperation $void
    ) {
    }

    public function fill(FillingDTOContract $data, $object = null): IGateway
    {
        if (get_class($data) === VoidDto::class) {
            $this->operation = $this->void;
        } elseif (get_class($data) === CaptureDto::class) {
            $this->operation = $this->capture;
        }

        return parent::fill($data, $object);
    }
}
