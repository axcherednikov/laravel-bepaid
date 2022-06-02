<?php

declare(strict_types=1);

namespace Excent\BePaidLaravel\Dtos;

use Excent\BePaidLaravel\Types\AdditionalDataType;
use Excent\BePaidLaravel\Types\MoneyType;

class ProductDto extends BaseDto
{
    public MoneyType $money;

    public AdditionalDataType $additional_data;

    public string $name;

    public string $description;

    public int $quantity;

    public bool $infinite_state;

    public bool $immortal_state;

    public string $transaction_type;

    public array $visible;

    public string $expired_at;
}
