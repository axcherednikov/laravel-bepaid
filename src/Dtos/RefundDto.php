<?php

declare(strict_types=1);

namespace Excent\BePaidLaravel\Dtos;

use Excent\BePaidLaravel\Contracts\FillingDTOContract;
use Excent\BePaidLaravel\Types\MoneyType;

class RefundDto extends BaseDto implements FillingDTOContract
{
    public string $reason;
    public string $parent_uid;
    public MoneyType $money;
}
