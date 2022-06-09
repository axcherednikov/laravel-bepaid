<?php

declare(strict_types=1);

namespace Excent\BePaidLaravel\Dtos;

use Excent\BePaidLaravel\Contracts\FillingDTOContract;
use Excent\BePaidLaravel\Types\MoneyType;

class VoidDto extends BaseDto implements FillingDTOContract
{
    public MoneyType $money;
    public string $parent_uid;
}
