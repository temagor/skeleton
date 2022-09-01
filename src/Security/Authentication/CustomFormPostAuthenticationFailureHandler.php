<?php

namespace App\Security\Authentication;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface;

class CustomFormPostAuthenticationFailureHandler implements AuthenticationFailureHandlerInterface
{

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): Response
    {
        return new JsonResponse(['success' => false, 'message' => $exception->getMessage(), 'data' => []]);
    }
}
