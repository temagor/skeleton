<?php

namespace App\Exception;

use Exception;
use Throwable;

class ValidateException extends Exception
{
    function __construct(string $message = "", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous = null);
    }
}
