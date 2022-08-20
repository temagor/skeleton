<?php

namespace App\Contracts;

use Symfony\Component\HttpFoundation\JsonResponse;

interface JsonResponseContract
{
    public function getJsonResponse(): JsonResponse;
}
