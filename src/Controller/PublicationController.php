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
        $userId = $this->getUser(); // Récupère l'ID de l'utilisateur connecté
        $posts = $manager->getRepository(Post::class)->findBy(['created_by' => $userId]);

        return $this->render('publication/index.html.twig', [
            'posts' => $posts,
        ]);
    }

}
