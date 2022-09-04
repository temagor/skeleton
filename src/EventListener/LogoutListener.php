<?php

namespace App\EventListener;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Http\Event\LogoutEvent;

class LogoutListener
{
    public function onSymfonyComponentSecurityHttpEventLogoutEvent(LogoutEvent $event)
    {
        $event->setResponse(new JsonResponse([
            'success' => true,
            'message' => 'You are loggin out from us. So sorry',
            'data' => [],
        ]));
    }
}
