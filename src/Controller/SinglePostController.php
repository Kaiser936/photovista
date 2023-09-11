<?php

namespace App\Controller;

use App\Entity\Commentaire;
use App\Entity\Post;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SinglePostController extends AbstractController
{
    /**
     * @Route("/single/post/{id}", name="app_single_post")
     */
    public function index($id, EntityManagerInterface $manager): Response
    {
        $post = $manager->getRepository(Post::class)->find($id);

        if (!$post) {
            throw $this->createNotFoundException('Cet article n\'existe pas');
        }

        return $this->render('single_post/index.html.twig', [
            'post' => $post,
        ]);
    }


        /**
     * @Route("/single/post/remove/{id}", name="app_single_post_remove")
     */
    public function remove($id, EntityManagerInterface $manager)
    {
        //  0- recuperer l'article en question
        $post = $manager->getRepository(Post::class)->find($id);
        //  1- supprimer l'article en question 
        $manager->remove($post);
        $manager->flush();  
        //  2- redirection sur la page d'accueil
        return $this->redirectToRoute('app_publication');
    }

    public function Commentaire($id, EntityManagerInterface $manager, Request $request): Response
    {
        // Récupérer le commentaire en fonction de l'ID
        $commentaire = $manager->getRepository(Commentaire::class)->find($id);
    
        // Créer le formulaire de commentaire
        $form = $this->createForm(CommentaireType::class, $commentaire);
    
        // Gérer la soumission du formulaire
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            // Enregistrer le commentaire dans la base de données
            $manager->persist($commentaire);
            $manager->flush();
    
            // Rediriger l'utilisateur vers la page appropriée
            // (par exemple, la page de l'article avec le commentaire)
            return $this->redirectToRoute('app_single_post');
        }
    
        return $this->render('single_post/commentaire.html.twig', [
            'commentaire' => $commentaire,
            'form' => $form->createView(), // Assurez-vous d'envoyer la vue du formulaire
        ]);
    }
    
}