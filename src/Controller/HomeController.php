<?php

namespace App\Controller;

use App\Entity\Post;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
class HomeController extends AbstractController
{
    /**
     * @Route("/", name="app_home")
     */
    public function index(EntityManagerInterface $manager): Response
    {
        // Récupérez les 4 dernières publications
        $dernieresPublications = $manager->getRepository(Post::class)
            ->findBy([], ['createdAt' => 'DESC'], 4);
        /** On récupère les 4 dernières publications en triant par ordre décroissant de 'createdAt' (date de création) */
    
        return $this->render('home/index.html.twig', [
            'dernieresPublications' => $dernieresPublications,
        ]);
        /** On rend la page "home/index.html.twig" en transmettant la liste des 4 dernières publications à la vue */
    }
    

    /**
     * @Route("/legal/cgu", name="app_legal_cgu")
     */
    public function cgu(): Response
    {
        return $this->render('legal/cgu.html.twig');
    }

    /**
     * @Route("/legal/confi", name="app_legal_confi")
     */
    public function confid(): Response
    {
        return $this->render('legal/confi.html.twig');
    }

    /**
     * @Route("/legal/rgpd", name="app_legal_rgpd")
     */
    public function rgpd(): Response
    {
        return $this->render('legal/rgpd.html.twig');
    }

}
