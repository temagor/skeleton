<?php

namespace App\Adapter\Request;

use App\Entity\Credential;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\Security;

class RequestCredentialListAdapter
{
    protected ?User $user;

    function __construct(
        protected RequestStack $requestStack,
        protected UserPasswordHasherInterface $userPasswordHasher,
        protected Security $security
    ) {
        $this->user = $security->getUser();
    }

    public function hasUser(): bool
    {
        return ($this->user instanceof User);
    }

    public function setUser(User $user): self
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return Credential[]
     * @throws BadRequestException 
     * @throws UserNotFoundException 
     */
    public function getCredentialList(): array
    {
        $credentialList = $this->requestStack->getCurrentRequest()?->get('credentialList');
        $credentials = [];
        foreach ($credentialList as $credentialFields) {
            $credential = new Credential;
            $credential->setType($credentialFields['type'] ?? null);
            if ($credential->getType() === 'login') {
                if (!$this->user instanceof User) {
                    throw new UserNotFoundException();
                }
                if (!array_key_exists('value', $credentialFields)) {
                    throw new BadRequestException('value not found');
                }
                $credential->setValue($this->userPasswordHasher->hashPassword($this->user, $credentialFields['value']));
            }
            $credentials[] = $credential;
        }

        return $credentials;
    }
}
