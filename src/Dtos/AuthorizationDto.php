<?php

declare(strict_types=1);

namespace Excent\BePaidLaravel\Dtos;

use Excent\BePaidLaravel\Types\{AdditionalDataType, CardType, CustomerType, MoneyType};
use Excent\BePaidLaravel\Contracts\FillingDTOContract;

class AuthorizationDto extends BaseDto implements FillingDTOContract
{
    public CustomerType $customer;
    public CardType $card;
    public MoneyType $money;
    public AdditionalDataType $additional_data;
    public string $description;
    public string $tracking_id;
    public ?bool $test;
    public ?string $language;
}
