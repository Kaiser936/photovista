<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\Commentaire;
use App\Form\CommentaireType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SinglePostController extends AbstractController
{
    /**
     * @Route("/single/post/{id}", name="app_single_post")
     */
    public function index($id, EntityManagerInterface $manager, Request $request): Response
    {
        // Récupère la publication spécifiée par son ID depuis la base de données
        $post = $manager->getRepository(Post::class)->find($id);

        // Récupère l'utilisateur actuellement connecté
        $user = $this->getUser();

        // Crée un nouveau commentaire et un formulaire pour saisir le commentaire
        $comment = new Commentaire();
        $form = $this->createForm(CommentaireType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            // Définit la date du commentaire comme la date actuelle
            $comment->setDate(new \DateTime());

            // Associe l'auteur du commentaire à l'utilisateur connecté
            $comment->setAuteur($user);

            // Associe la publication au commentaire
            $comment->setPost($post);

            // Persister le commentaire en base de données
            $manager->persist($comment);
            $manager->flush();

            // Redirige vers la page de la publication une fois le commentaire soumis
            return $this->redirectToRoute('app_single_post', ['id' => $id]);
        }

        // Récupère les commentaires associés à la publication
        $commentaires = $post->getCommentaires();

        // Rend la page de la publication unique ("single_post/index.html.twig") en passant la publication, le formulaire et les commentaires à la vue
        return $this->render('single_post/index.html.twig', [
            'post' => $post,
            'form' => $form->createView(),
            'commentaires' => $commentaires,
        ]);
    }



    /**
     * @Route("/single/post/remove/{id}", name="app_single_post_remove")
     */
    public function remove($id, EntityManagerInterface $manager)
    {
        // Récupérer la publication en utilisant son ID
        $post = $manager->getRepository(Post::class)->find($id);
        
        // Supprimer la publication en question de la base de données
        $manager->remove($post);
        $manager->flush();
    
        // Rediriger l'utilisateur vers la page d'accueil des publications après la suppression
        return $this->redirectToRoute('app_publication');
    }
    

    /**
     * @Route("/single/post/comment_remove/{id}", name="app_single_post_comment_remove")
     */
    public function comment_remove($id, EntityManagerInterface $manager)
    {
        // Récupérer le commentaire en utilisant son ID
        $comment = $manager->getRepository(Commentaire::class)->find($id);

        // Récupérez l'ID du post associé au commentaire
        $postId = $comment->getPost()->getId();

        // Supprimer le commentaire de la base de données
        $manager->remove($comment);
        $manager->flush();

        // Redirigez vers la page du post après la suppression du commentaire
        return $this->redirectToRoute('app_single_post', ['id' => $postId]);
    }   
}