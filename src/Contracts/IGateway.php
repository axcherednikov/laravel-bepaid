<?php

declare(strict_types=1);

namespace Excent\BePaidLaravel\Contracts;

use BeGateway\ResponseBase;

interface IGateway
{
    public function submit(FillingDTOContract $data = null): ResponseBase;

    public function fill(FillingDTOContract $data, $object = null): self;
}
