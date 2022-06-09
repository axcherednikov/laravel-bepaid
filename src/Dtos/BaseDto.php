<?php

declare(strict_types=1);

namespace Excent\BePaidLaravel\Dtos;

use Illuminate\Support\Str;

abstract class BaseDto
{
    public function __construct(?array $attributes = null)
    {
        if ($attributes && count($attributes)) {
            $this->fill($attributes, $this);
        }
    }

    private function fill(array $attributes, $object)
    {
        $obj = $object ?? $this;

        foreach ($attributes as $attribute => $value) {
            if (property_exists($obj, $attribute)) {
                if (is_array($value)) {
                    $class = "Excent\\BePaidLaravel\\Types\\" . ucfirst(Str::camel($attribute)) . 'Type';

                    if (class_exists($class)) {
                        $obj->{$attribute} = $this->fill($value, (new $class));
                    } else {
                        $obj->{$attribute} = $value;
                    }
                } else {
                    $obj->{$attribute} = $value;
                }
            }
        }

        if (! $object instanceof static) {
            return $object;
        }
    }
}
