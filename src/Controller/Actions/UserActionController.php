<?php

namespace App\Controller\Actions;

use App\Actions\User\SignUpAction;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/user/actions/api/', name: 'app:user:actions:api:')]
class UserActionController extends AbstractController
{
    #[Route('sign-up', name: 'sign-up', methods: ['POST'])]
    public function signUp(SignUpAction $signUpAction): JsonResponse
    {
        $signUpAction->handle();
        return new JsonResponse(
            [
                'success' => $signUpAction->isSuccess(),
                'message' => $signUpAction->getMessage(),
                'data' => $signUpAction->getData()
            ]
        );
    }

    #[Route('sign-in', name: 'sign-in', methods: ['POST'])]
    public function signIn(): JsonResponse
    {
        return new JsonResponse(['success' => true, 'message' => 'You have successfully logged in', 'data' => []]);
    }

    #[Route('sign-out', name: 'sign-out', methods: ['POST'])]
    public function signOut(): JsonResponse
    {
        return new JsonResponse(['success' => true, 'message' => 'You have successfully logged out', 'data' => []]);
    }
}
