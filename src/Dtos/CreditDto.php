<?php

declare(strict_types=1);

namespace Excent\BePaidLaravel\Dtos;

use Excent\BePaidLaravel\Types\{CardType, MoneyType};
use Excent\BePaidLaravel\Contracts\FillingDTOContract;

class CreditDto extends BaseDto implements FillingDTOContract
{
    public CardType $card;
    public MoneyType $money;
    public string $description;
    public string $tracking_id;
}
