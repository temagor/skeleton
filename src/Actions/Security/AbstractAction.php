<?php

namespace App\Actions\Security;

use App\Contracts\ActionContract;
use App\Entity\User;
use App\Repository\CredentialRepository;
use App\Repository\UserRepository;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Security;

abstract class AbstractAction implements ActionContract
{
    protected bool $success;
    protected string $message;
    protected array $data = [];

    function __construct(
        protected Security $security,
        protected UserPasswordHasherInterface $userPasswordHasher,
        protected TokenStorageInterface $tokenStorage,
        protected UserRepository $userRepository,
        protected CredentialRepository $credentialRepository
    ) {
    }

    abstract public function handle(): void;

    protected function isAuthentificated(): bool
    {
        return $this->security->getUser() instanceof User;
    }

    protected function getUser(): ?User
    {
        return $this->security->getUser();
    }

    public function isSuccess(): bool
    {
        return $this->success;
    }

    protected function setSuccess(bool $success): self
    {
        $this->success = $success;
        return $this;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    protected function setMessage(string $message): self
    {
        $this->message = $message;
        return $this;
    }

    public function getData(): array
    {
        return $this->data;
    }

    protected function setData(array $data)
    {
        $this->data = $data;
    }
}
