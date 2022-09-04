<?php

namespace App\Adapter\Request;

use App\Entity\Credential;
use App\Entity\User;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Container\ContainerExceptionInterface;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Security;

class RequestCredentialListAdapter
{
    protected ?User $user;

    /**
     * @param RequestStack $requestStack 
     * @param UserPasswordHasherInterface $userPasswordHasher 
     * @param Security $security 
     * @return void 
     * @throws NotFoundExceptionInterface 
     * @throws ContainerExceptionInterface 
     */
    function __construct(
        protected RequestStack $requestStack,
        protected Security $security
    ) {
        $this->user = $security->getUser();
    }

    /** @return bool  */
    public function hasUser(): bool
    {
        return ($this->user instanceof User);
    }

    /**
     * @param User $user 
     * @return RequestCredentialListAdapter 
     */
    public function setUser(User $user): self
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return array 
     * @throws BadRequestException 
     */
    public function getCredentialList(): array
    {
        $credentialList = $this->requestStack->getCurrentRequest()?->get('credentialList');
        $credentials = [];
        foreach ($credentialList as $credentialType => $credentialValue) {
            if (empty($credentialValue)) {
                continue;
            }
            $credential = new Credential;
            $credential->setType($credentialType);
            $credentials[] =  $credential->setValue($credentialValue);
        }

        return $credentials;
    }
}
