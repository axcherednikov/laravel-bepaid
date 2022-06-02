<?php

namespace Excent\BePaidLaravel\Dtos;

use Excent\BePaidLaravel\Types\MoneyType;

class RefundDto extends BaseDto
{
    public string $reason;

    public string $parent_uid;

    public MoneyType $money;
}
