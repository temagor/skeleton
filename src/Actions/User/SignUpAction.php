<?php

namespace App\Actions\User;

use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use App\Adapter\Request\RequestCredentialListAdapter;
use App\Adapter\Request\RequestUserAdapter;
use App\Entity\Credential;
use App\Actions\User\StoreAction as UserStoreAction;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use App\Exception\Validate\UserException;
use App\Actions\Security\AuthentificateAction as UserAuthentificateAction;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Container\ContainerExceptionInterface;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class SignUpAction extends AbstractAction
{
    protected User $user;
    /** @var Credential[] $credentialList */
    protected array $credentialList;

    /**
     * @param RequestUserAdapter $requestUserAdapter 
     * @param RequestCredentialListAdapter $requestCredentialListAdapter 
     * @param StoreAction $userStoreAction 
     * @param UserAuthentificateAction $authentificateAction 
     * @return void 
     * @throws BadRequestException 
     */
    function __construct(
        protected RequestUserAdapter $requestUserAdapter,
        protected RequestCredentialListAdapter $requestCredentialListAdapter,
        protected UserStoreAction $userStoreAction,
        protected UserAuthentificateAction $authentificateAction
    ) {
        $this->user = $requestUserAdapter->getUser();
        $requestCredentialListAdapter->setUser($this->user);
        $this->credentialList = $requestCredentialListAdapter->getCredentialList();
    }

    /**
     * @return void 
     * @throws UserNotFoundException 
     * @throws UserException 
     * @throws AuthenticationException 
     * @throws NotFoundExceptionInterface 
     * @throws ContainerExceptionInterface 
     */
    public function handle(): void
    {
        foreach ($this->credentialList as $credential) {
            $this->user->addCredential($credential);
        }

        $this->userStoreAction->setUser($this->user);
        $this->userStoreAction->handle();
        $this->authentificateAction->setUser($this->user)->handle();
        $this->setSuccess($this->authentificateAction->isSuccess());
        $this->setMessage('You have successfully registered');
    }
}
