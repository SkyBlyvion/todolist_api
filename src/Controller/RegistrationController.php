<?php

namespace App\Controller;

use DateTime;
use App\Entity\User;
use App\Security\UserAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register', methods: ['POST'])]
    public function register(
        Request $request,
        UserPasswordHasherInterface $userPasswordHasher,
        UserAuthenticatorInterface $userAuthenticator,
        UserAuthenticator $authenticator,
        EntityManagerInterface $entityManager
    ): Response {
        //on récupère les datas envoyé par le front (react)
        $data = json_decode($request->getContent(), true);
        //on crée un nouvel utilisateur
        $user = new User();
        //on lui set ses paramètres
        $user->setEmail($data['email']);
        $user->setName($data['name']);
        $user->setPassword(
            $userPasswordHasher->hashPassword(
                $user,
                $data['password']
            )
        );
        $user->setCreatedAt(new DateTime());
        //on persist l'utilisateur
        $entityManager->persist($user);
        //on flush l'utilisateur
        $entityManager->flush();

        //on retourne la réponse pour le front
        return $userAuthenticator->authenticateUser(
            $user,
            $authenticator,
            $request
        );
    }
}
