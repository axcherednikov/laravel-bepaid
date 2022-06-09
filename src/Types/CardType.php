<?php

namespace Excent\BePaidLaravel\Types;

use Excent\BePaidLaravel\Contracts\FillingDTOContract;

class CardType implements FillingDTOContract
{
    public $card_number;
    public $card_holder;
    public $card_exp_month;
    public $card_exp_year;
    public $card_cvc;
    public $first_one;
    public $last_four;
    public $brand;
    public $card_token = null;
    public $card_skip_three_d_secure = false;
    public $encrypted = false;
}
