<?php

namespace App\Controller;

use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): JsonResponse
    {
        //on vérifie que l'utilisateur est connecté
        if ($this->getUser()) {
            return new JsonResponse([
                'success' => true,
                'id' => $this->getUser()->getId(),
                'name' => $this->getUser()->getName(),
                'email' => $this->getUser()->getEmail(),
                'message' => 'Utilisateur déjà connecté'
            ]);
        }
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();
        dd($this->getUser());
        return new JsonResponse([
            'success' => true,
            'id' => $this->getUser()->getId(),
            'name' => $this->getUser()->getName(),
            'email' => $this->getUser()->getEmail(),
            'message' => 'Connexion réussie',
            'last_username' => $lastUsername,
            'error' => $error?->getMessage()
        ]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
