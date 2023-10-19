<?php

namespace App\Controller;

use App\Entity\Post;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProfilController extends AbstractController
{
    /**
     * @Route("/profil", name="app_profil")
     */
    public function index(EntityManagerInterface $manager): Response
    {
        // Récupère l'utilisateur actuellement connecté
        $user = $this->getUser();

        // Récupère les publications de l'utilisateur à partir de la base de données
        $userPosts = $manager->getRepository(Post::class)->findBy(['created_by' => $user]);

        // Rend la page de profil ("index.html.twig") en passant l'utilisateur (user) et ses publications (userPosts)
        return $this->render('profil/index.html.twig', [
            'user' => $user,
            'userPosts' => $userPosts,
        ]);
    }

}
