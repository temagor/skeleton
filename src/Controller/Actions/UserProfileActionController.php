<?php

namespace App\Controller\Actions;

use App\Repository\ProfileRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/profile/actions/api/{login}/', name: 'app:profile:actions:api:')]
class UserProfileActionController extends AbstractController
{
    #[Route('update', name: 'update')]
    public function update($login, Request $request, UserRepository $userRepository): Response
    {
        $user = $userRepository->findOneBy(['login' => $login]);
        $credentials = $user->getCredentials();
        $profile = $user->getProfile();
        return new JsonResponse(['success' => true, 'message' => 'updated', 'data' => []]);
    }
}
