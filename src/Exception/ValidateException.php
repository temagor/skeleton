<?php

namespace App\Exception;

use App\Contracts\JsonResponseContract;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Throwable;

class ValidateException extends Exception implements JsonResponseContract
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
        return new JsonResponse(['success' => false, 'message' => 'request validation error', 'errors' => $errorList], Response::HTTP_BAD_REQUEST);
    }
}
