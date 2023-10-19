<?php
namespace App\Controller;

use App\Entity\Post;
use Doctrine\ORM\EntityManagerInterface;
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
    // Récupérer tous les posts
    $posts = $manager->getRepository(Post::class)->findAll();
    /** On récupère tous les enregistrements de la classe Post depuis la base de données */

    return $this->render('galerie/index.html.twig', [
        'posts' => $posts,
    ]);
    /** On rend la page "galerie/index.html.twig" en transmettant la liste de tous les posts à la vue */
}

}
