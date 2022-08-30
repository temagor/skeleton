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

class SignUpAction extends AbstractAction
{
    protected User $user;
    /** @var Credential[] $credentialList */
    protected array $credentialList;
    protected Request $request;

    function __construct(
        protected RequestUserAdapter $requestUserAdapter,
        protected RequestCredentialListAdapter $requestCredentialListAdapter,
        protected UserStoreAction $userStoreAction,
        protected UserAuthentificateAction $authentificateAction
    ) {
        $this->user = $requestUserAdapter->getUser();
        if (!$requestCredentialListAdapter->hasUser()) {
            $requestCredentialListAdapter->setUser($this->user);
        }
        $this->credentialList = $requestCredentialListAdapter->getCredentialList();
    }

    /**
     * @return void 
     * @throws UserNotFoundException 
     * @throws UserException 
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
