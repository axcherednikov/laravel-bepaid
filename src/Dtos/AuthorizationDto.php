<?php

namespace Excent\BePaidLaravel\Dtos;

use Excent\BePaidLaravel\Types\{AdditionalDataType, CardType, CustomerType, MoneyType};

class AuthorizationDto extends BaseDto
{
    public CustomerType $customer;

    public CardType $card;

    public MoneyType $money;

    public AdditionalDataType $additional_data;

    public string $description;

    public string $tracking_id;
}
