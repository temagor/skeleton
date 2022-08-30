<?php

namespace App\Actions\Security;

use App\Entity\User;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authenticator\Token\PostAuthenticationToken;

class AuthentificateAction extends AbstractAction
{
    protected ?User $user;

    public function handle(): void
    {
        if (!$this->user instanceof User) {
            throw new AuthenticationException('User not set');
        }
        $token = new PostAuthenticationToken($this->user, 'main', $this->user->getRoles());
        $this->tokenStorage->setToken($token);
        $this->setSuccess($this->isAuthentificated());
        if ($this->isSuccess()) {
            $this->setMessage("user authentificated");
        }
    }

    public function setUser(User $user): self
    {
        $this->user = $user;
        return $this;
    }
}
