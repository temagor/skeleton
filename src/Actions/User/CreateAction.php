<?php

namespace App\Actions\User;

use App\Actions\AbstractAction;
use App\Contracts\UserActionContract;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class CreateAction extends AbstractAction implements UserActionContract
{
    function __construct(protected User $user, protected EntityManagerInterface $entityManager)
    {
    }

    public function handle(): User
    {
        $this->entityManager->persist($this->user);
        $this->entityManager->flush();
        if ($this->user->getId()) {
            $this
                ->setSuccess(true)
                ->setMessage('User created');
        }
        return $this->user;
    }
}
