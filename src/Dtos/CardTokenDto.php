<?php

namespace Excent\BePaidLaravel\Dtos;

use Excent\BePaidLaravel\Types\CardType;

class CardTokenDto extends BaseDto
{
    public CardType $card;
}
