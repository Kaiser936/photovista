<?php

namespace App\Controller;

use DateTime;
use App\Entity\Post;
use App\Entity\Comment;
use App\Form\CommentType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class GalerieController extends AbstractController
{
    /**
     * @Route("/galerie", name="app_galerie")
     */
    public function index(EntityManagerInterface $manager): Response
    {
        $post = $manager->getRepository(Post::class)->findAll();

        return $this->render('galerie/index.html.twig', [
            'post' => $post,
        ]);
    }
}
