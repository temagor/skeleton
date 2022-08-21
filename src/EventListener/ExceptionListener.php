<?php

namespace App\EventListener;

use App\Contracts\JsonResponseContract;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

class ExceptionListener
{
    public function onKernelException(ExceptionEvent $event)
    {
        $exception = $event->getThrowable();
        if (
            $event->getRequest()->headers->contains('Content-Type', 'application/json') &&
            $exception instanceof JsonResponseContract
        ) {
            $event->setResponse($exception->getJsonResponse());
        }
    }
}
