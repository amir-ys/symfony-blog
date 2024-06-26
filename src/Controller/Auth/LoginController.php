<?php

namespace App\Controller\Auth;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends AbstractController
{
    #[Route(path: '/login', name: 'auth.login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        return $this->render('auth/login.html.twig', [
            'error' => $error,
        ]);
    }

    #[Route(path: '/logout', name: 'auth.logout')]
    public function logout(Security $security)
    {
        $security->logout(false);
        return $this->render('auth/login.html.twig');
    }
}
