<?php

namespace Excent\BePaidLaravel\Dtos;

use Excent\BePaidLaravel\Types\{CardType, MoneyType};

class CreditDto extends BaseDto
{
    public CardType $card;

    public MoneyType $money;

    public string $description;

    public string $tracking_id;
}
