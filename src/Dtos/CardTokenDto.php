<?php

declare(strict_types=1);

namespace Excent\BePaidLaravel\Dtos;

use Excent\BePaidLaravel\Contracts\FillingDTOContract;
use Excent\BePaidLaravel\Types\CardType;

class CardTokenDto extends BaseDto implements FillingDTOContract
{
    public CardType $card;
}
