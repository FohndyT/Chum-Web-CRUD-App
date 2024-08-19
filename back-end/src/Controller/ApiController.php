<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class ApiController extends AbstractController
{
    #[Route(path: '/api/login', name: 'api_login', methods: ['POST'])]
    public function login(AuthenticationUtils $authenticationUtils, Security $security): JsonResponse
    {
        $error = $authenticationUtils->getLastAuthenticationError();

        if ($error)
        {
            return new JsonResponse([
                'success' => false,
                'message' => 'Email ou mot de passe incorrect',
                'error' => $error->getMessage(),
            ], Response::HTTP_UNAUTHORIZED);
        }

        $user = $this->getUser();

        if($user)
        {
            $userData = [
                'id' => $user->getId(),
                'email' => $user->getEmail(),
                'firstName' => $user->getFirstName(),
                'lastName' => $user->getLastName(),
                'roles' => $user->getRoles(),
                'profiles' => $user->getProfiles(),
                'projects' => $user->getProjects(),
            ];

            return new JsonResponse([
                'success' => true,
                'message' => 'Connexion réussie',
                'user' => $userData,
            ]);
        }

        return new JsonResponse([
            'success' => false,
            'message' => 'Un problème est survenu',
        ], Response::HTTP_UNAUTHORIZED);
    }

    #[Route(path: '/api/logout', name: 'api_logout', methods: ['POST'])]
    public function logout(): JsonResponse
    {
        return new JsonResponse(['success' => true, 'message' => 'Déconnexion réussie']);
    }

    #[Route(path: '/api/test', name: 'api_test', methods: ['GET'])]
    public function getData(): JsonResponse
    {
        $data = [
            'message' => 'Hello from Symfony!',
            // Add more data as needed
        ];

        return new JsonResponse($data);
    }
}
