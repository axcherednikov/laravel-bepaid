<?php

namespace Excent\BePaidLaravel\Types;

use Excent\BePaidLaravel\Contracts\FillingDTOContract;

class CustomerType implements FillingDTOContract
{
    public $ip;
    public $email;
    public $first_name;
    public $last_name;
    public $address;
    public $city;
    public $country;
    public $state;
    public $zip;
    public $phone;
    public $birth_date = NULL;
}
