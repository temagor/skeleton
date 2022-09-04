<?php

namespace App\Adapter\Request;

use App\Entity\User;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RequestUserAdapter
{
    private User $user;

    function __construct(private RequestStack $requestStack, private UserPasswordHasherInterface $userPasswordHasher)
    {
        $userFields = $requestStack->getCurrentRequest()?->get('user');
        // TODO: add RequestUserAdapter error
        $login = $userFields['login'] ?? 'John Doe';
        $plainPassword = $userFields['password'] ?? '!@ChangeMe!';
        $protected = $userFields['protected'] ?? false;
        $this->user = (new User)
            ->setLogin($login)
            ->setProtected($protected);
        $this->user->setPassword($this->userPasswordHasher->hashPassword($this->user, $plainPassword));
    }

    public function getUser(): User
    {
        return $this->user;
    }
}
