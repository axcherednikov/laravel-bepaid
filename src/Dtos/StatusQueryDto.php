<?php

declare(strict_types=1);

namespace Excent\BePaidLaravel\Dtos;

use Excent\BePaidLaravel\Contracts\FillingDTOContract;

class StatusQueryDto implements FillingDTOContract
{
    public string $token;
}
