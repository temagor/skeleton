<?php

namespace App\Adapter\Request;

use App\Entity\User;
use Symfony\Component\HttpFoundation\RequestStack;

class RequestUserAdapter
{
    private User $user;

    function __construct(private RequestStack $requestStack)
    {
        $userFields = $requestStack->getCurrentRequest()?->get('user');
        $this->user = (new User)
            ->setLogin($userFields['login'])
            ->setProtected($userFields['protected']);
    }

    public function getUser(): User
    {
        return $this->user;
    }
}
