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
    public function index($id, EntityManagerInterface $manager,Request $request): Response
    {
        $post = $manager->getRepository(Post::class)->find($id);

        $user = $this->getUser();

        $comment = new Commentaire();
        $form = $this->createForm(CommentaireType::class,$comment);
        $form->handleRequest($request);
        
        if($form->isSubmitted()){
            $comment->setDate(new \DateTime());
            $comment->setAuteur($user);
            $comment->setPost($post);
            $manager->persist($comment);
            $manager->flush();
            return $this->redirectToRoute('app_single_post', ['id' => $id]);
        }

        // Récupérez les commentaires associés au post
        $commentaires = $post->getCommentaires();

        return $this->render('single_post/index.html.twig', [
            'post' => $post,
            'form' => $form->createView(),
            'commentaires' => $commentaires, // Passez les commentaires au modèle Twig
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

/**
 * @Route("/single/post/comment_remove/{id}", name="app_single_post_comment_remove")
 */
public function comment_remove($id, EntityManagerInterface $manager)
{
    $comment = $manager->getRepository(Commentaire::class)->find($id);

    $postId = $comment->getPost()->getId(); // Récupérez l'ID du post associé au commentaire

    $manager->remove($comment);
    $manager->flush();

    // Redirigez vers la page du post après la suppression du commentaire
    return $this->redirectToRoute('app_single_post', ['id' => $postId]);
}

}