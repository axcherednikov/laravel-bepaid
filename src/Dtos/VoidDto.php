<?php

namespace Excent\BePaidLaravel\Dtos;

use Excent\BePaidLaravel\Types\MoneyType;

class VoidDto extends BaseDto
{
    public MoneyType $money;

    public string $parent_uid;
}
