<?php

namespace App\Actions\User;

use App\Entity\User;
use App\Exception\Validate\UserException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class StoreAction extends AbstractAction
{
    protected User $user;

    function __construct(protected ValidatorInterface $validator, protected EntityManagerInterface $entityManager)
    {
    }

    public function setUser(User $user): self
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return void
     * @throws UserNotFoundException 
     * @throws UserException 
     */
    public function handle(): void
    {
        if (!$this->user instanceof User) {
            throw new UserNotFoundException();
        }

        foreach ($this->user->getCredentials() as $credential) {
            /** @var ConstraintViolationListInterface $errors */
            if (($errors = $this->validator->validate($credential))->count() !== 0) {
                throw new UserException($errors, Response::HTTP_BAD_REQUEST);
            }
        }

        /** @var ConstraintViolationListInterface $errors */
        if (($errors = $this->validator->validate($this->user))->count() !== 0) {
            throw new UserException($errors, Response::HTTP_BAD_REQUEST);
        }

        $this->entityManager->persist($this->user);
        $this->entityManager->flush();
        if ($this->user->getId()) {
            $this
                ->setSuccess(true)
                ->setMessage('User created');
        }
    }
}
