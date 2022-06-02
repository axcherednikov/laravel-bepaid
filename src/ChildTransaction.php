<?php

namespace Excent\BePaidLaravel;

use BeGateway\{Response, ResponseBase};
use BeGateway\CaptureOperation;
use BeGateway\VoidOperation;
use Excent\BePaidLaravel\Contracts\IGateway;
use Excent\BePaidLaravel\Dtos\{CaptureDto, VoidDto};

class ChildTransaction extends GatewayAbstract
{
    public VoidOperation|CaptureOperation $operation;

    private VoidOperation $void;

    private CaptureOperation $capture;

    public function __construct(CaptureOperation $capture, VoidOperation $void)
    {
        $this->capture = $capture;
        $this->void = $void;
    }

    /**
     * @param VoidDto|CaptureDto $data
     *
     * @return Response
     * @throws \Exception
     */
    public function submit($data = null): ResponseBase
    {
        return parent::submit($data);
    }

    /**
     * @param VoidDto|CaptureDto                                   $data
     * @param null|\BeGateway\Money|VoidOperation|CaptureOperation $object
     *
     * @return \Excent\BePaidLaravel\Contracts\IGateway
     */
    public function fill($data, $object = null): IGateway
    {
        switch (get_class($data)) {
            case VoidDto::class:
                $this->operation = $this->void;
                break;
            case CaptureDto::class:
                $this->operation = $this->capture;
                break;
        }

        return parent::fill($data, $object);
    }
}
