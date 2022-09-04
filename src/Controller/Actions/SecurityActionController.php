<?php

namespace App\Controller\Actions;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/security/actions/api/', name: 'app:security:actions:api:')]
class SecurityActionController extends AbstractController
{
    #[Route('get-authentificated-user', name: 'get_authentificated_user')]
    public function getAuthentificatedUser(): JsonResponse
    {
        return new JsonResponse(
            [
                'success' => true,
                'message' => '',
                'data' => $this->getUser() instanceof User ? $this->getUser() : []
            ]
        );
    }
}
