<?php

namespace App\Exception\Validate;

use App\Contracts\JsonResponseContract;
use App\Exception\ValidateException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Throwable;

class UserException extends ValidateException implements JsonResponseContract
{
    function __construct(protected ConstraintViolationListInterface $errors, int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct((string) $errors, $code, $previous = null);
    }

    public function getJsonResponse(): JsonResponse
    {
        $errorList = [];
        /** @var ConstraintViolationInterface $constraintViolationError */
        foreach ($this->errors as $constraintViolationError) {
            $errorList[$constraintViolationError->getPropertyPath()] = $constraintViolationError->getMessage();
        }
        return new JsonResponse(['success' => false, 'message' => 'validation error', 'errors' => $errorList], Response::HTTP_BAD_REQUEST);
    }
}
