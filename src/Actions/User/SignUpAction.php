<?php

namespace App\Actions\User;

use App\Actions\AbstractAction;
use App\Contracts\ActionContract;
use App\Entity\Credential;
use App\Entity\User;
use App\Exception\Validate\UserException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class SignUpAction extends AbstractAction implements ActionContract
{
    private User $user;

    function __construct(
        protected RequestStack $requestStack,
        protected UserPasswordHasherInterface $userPasswordHasher,
        protected ValidatorInterface $validator,
        protected EntityManagerInterface $entityManager,
    ) {
        $request = $requestStack->getCurrentRequest();
        $this->user = new User;
        $this->user->fill($request);
        $this->fillUserCredential($request);
        /** @var ConstraintViolationListInterface $errors */
        if (($errors = $validator->validate($this->user))->count() !== 0) {
            throw new UserException($errors, Response::HTTP_BAD_REQUEST);
        }
    }

    private function fillUserCredential(Request $request)
    {
        $credentialList = $request->get('credentialList');
        foreach ($credentialList as $credentialFields) {
            $credential = new Credential();
            $credential->setType($credentialFields['type']);
            if ($credential->getType() === 'login') {
                $credential->setValue($this->userPasswordHasher->hashPassword($this->user, $credentialFields['plainPassword']));
            }
            if (($errors = $this->validator->validate($credential))->count() !== 0) {
                throw new UserException($errors, Response::HTTP_BAD_REQUEST);
            }
            $this->user->addCredential($credential);
        }
    }

    public function handle()
    {
        (new CreateAction($this->user, $this->entityManager))->handle();
        // (new AuthenticateAction($this->entityManager, $this->user))->handle(); // SecurityAction
        $this->setSuccess(true);
        $this->setMessage('You have successfully registered');
    }
}
