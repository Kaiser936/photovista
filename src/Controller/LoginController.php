<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // Récupère toute erreur de connexion s'il y en a une
        $error = $authenticationUtils->getLastAuthenticationError();
    
        // Récupère le dernier nom d'utilisateur saisi par l'utilisateur
        $pseudo = $authenticationUtils->getLastUsername();
    
        // Rend la page de connexion ("login.html.twig") en passant le nom d'utilisateur (pseudo) et l'erreur de connexion (error)
        return $this->render('security/login.html.twig', ['pseudo' => $pseudo, 'error' => $error]);
    }
    
    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout(): void
    {
        // Cette méthode est vide et n'a pas besoin d'être implémentée. Elle sera interceptée par le pare-feu de sécurité Symfony pour gérer la déconnexion.
        // L'exception logique indique qu'aucun code n'est nécessaire dans cette méthode.
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
    
}
