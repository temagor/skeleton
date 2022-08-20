<?php

namespace App\EventListener;

use App\Contracts\JsonResponseContract;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class ExceptionListener
{
    public function onKernelException(ExceptionEvent $event)
    {
        $exception = $event->getThrowable();
        if ($exception instanceof JsonResponseContract) {
            $event->setResponse($exception->getJsonResponse());
        }
    }
}
