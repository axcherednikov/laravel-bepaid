<?php

namespace Excent\BePaidLaravel\Exceptions;

use Throwable;

class TransactionException extends \Exception
{
    private $errors;

    public function __construct($message = "", $errors = [], $code = 0, Throwable $previous = null)
    {
        $this->errors = $errors;

        parent::__construct($message, $code, $previous);
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function setErrors(array $errors)
    {
        $this->errors = $errors;
    }
}
