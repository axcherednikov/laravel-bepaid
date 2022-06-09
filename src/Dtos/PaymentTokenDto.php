<?php

declare(strict_types=1);

namespace Excent\BePaidLaravel\Dtos;

use Excent\BePaidLaravel\Contracts\FillingDTOContract;
use Excent\BePaidLaravel\Types\AdditionalDataType;
use Excent\BePaidLaravel\Types\CustomerType;
use Excent\BePaidLaravel\Types\MoneyType;

class PaymentTokenDto extends BaseDto implements FillingDTOContract
{
    public CustomerType $customer;
    public MoneyType $money;
    public AdditionalDataType $additional_data;
    public string $description;
    public string $tracking_id;
    public string $transaction_type;
    public array $readonly;
    public array $visible;
    public array $payment_methods;
    public string $expired_at;
    public int $attempts;
}
