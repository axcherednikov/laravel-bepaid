<?php

declare(strict_types=1);

namespace Excent\BePaidLaravel\Dtos;

use Excent\BePaidLaravel\Contracts\FillingDTOContract;

class QueryByUidDto extends BaseDto implements FillingDTOContract
{
    public string $uid;
}
