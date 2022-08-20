<?php

namespace App\Controller\Actions;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/user/actions/api/', name: 'app:user:actions:api:')]
class UserActionsController extends AbstractController
{
    #[Route('sign-in', name: 'sign-in', methods: ['POST'])]
    public function signIn(): JsonResponse
    {
        return new JsonResponse(['success' => true, 'message' => 'You have successfully logged in', 'data' => []]);
    }

    #[Route('sign-up', name: 'sign-up', methods: ['POST'])]
    public function signUp(): JsonResponse
    {
        return new JsonResponse(['success' => true, 'message' => 'You have successfully registered', 'data' => []]);
    }
}
