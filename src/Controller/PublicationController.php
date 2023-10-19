<?php

namespace App\Controller;

use App\Entity\Post;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PublicationController extends AbstractController
{
    /**
     * @Route("/publication", name="app_publication")
     */
    public function index(EntityManagerInterface $manager): Response
    {
        // Récupère l'ID de l'utilisateur connecté
        $userId = $this->getUser();
    
        // Récupère les publications de l'utilisateur à partir de la base de données en utilisant son ID
        $posts = $manager->getRepository(Post::class)->findBy(['created_by' => $userId]);
    
        // Rend la page de publications ("index.html.twig") en passant les publications (posts) à la vue
        return $this->render('publication/index.html.twig', [
            'posts' => $posts,
        ]);
    }
    

}
