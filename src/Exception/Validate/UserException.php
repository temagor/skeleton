<?php

namespace App\Exception\Validate;

use App\Exception\ValidateException;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Throwable;

final class UserException extends ValidateException
{
    function __construct(protected ConstraintViolationListInterface $errors, int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($errors, $code, $previous = null);
    }
}
