<?php

declare(strict_types=1);

namespace Excent\BePaidLaravel\Dtos;

use Excent\BePaidLaravel\Contracts\FillingDTOContract;

class QueryByTrackingIdDto extends BaseDto implements FillingDTOContract
{
    public string $tracking_id;
}
