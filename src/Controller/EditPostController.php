<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class EditPostController extends AbstractController
{
    /**
     * @Route("/editpost/{id}", name="app_edit_post")
     */
    public function index($id, EntityManagerInterface $manager, Request $request, SluggerInterface $slugger): Response
    {

        $post = $manager->getRepository(Post::class)->find($id);
        /** On récupère une instance de la classe Post depuis la base de données en utilisant son identifiant $id */
        
        $post->setPicture("");
        /** On réinitialise la propriété 'picture' de l'objet $post en la vidant */
        
        $form = $this->createForm(PostType::class, $post);
        /** On crée un formulaire en utilisant la classe PostType et on associe l'objet $post aux données du formulaire */
        
        $form->handleRequest($request);
        /** On traite la soumission du formulaire en fonction de la requête HTTP reçue (généralement une requête POST) */
        
        if ($form->isSubmitted()) {
            /** On vérifie si le formulaire a été soumis */
        
            $picture = $form->get('picture')->getData();
            /** On obtient les données du champ 'picture' du formulaire */
        
            if ($picture) {
                /** On vérifie si une image a été téléchargée via le formulaire */
        
                $originalFilename = pathinfo($picture->getClientOriginalName(), PATHINFO_FILENAME);
                /** On obtient le nom de fichier d'origine de l'image téléchargée (sans l'extension) */
        
                $safeFilename = $slugger->slug($originalFilename);
                /** On utilise un "slugger" pour générer un nom de fichier "sûr" en remplaçant les caractères spéciaux et en mettant en minuscules */
        
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $picture->guessExtension();
                /** On génère un nouveau nom de fichier en ajoutant un identifiant unique et en conservant l'extension d'origine */
        
                try {
                    /** On tente de déplacer l'image téléchargée vers le répertoire spécifié */
                    $picture->move(
                        $this->getParameter('pictures_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // En cas d'exception, on gère l'erreur ici si nécessaire
                }
        
                $post->setPicture($newFilename);
                /** On met à jour la propriété 'picture' de l'objet $post avec le nouveau nom de fichier */
            }
        
            $post->setUpdatedAt(new \DateTime());
            /** On met à jour la propriété 'updatedAt' de l'objet $post avec la date et l'heure actuelles */
        
            $manager->persist($post);
            $manager->flush();
            /** On persiste (enregistre) les modifications de l'objet $post dans la base de données */
        
            return $this->redirectToRoute('app_single_post', ['id' => $post->getId()]);
            /** On redirige l'utilisateur vers une autre page après le succès de la mise à jour du formulaire */
        }
        
        return $this->render('edit_post/index.html.twig', [
            'form' => $form->createView(),
        ]);
        /** Si le formulaire n'est pas soumis, on affiche la page d'édition de publication avec le formulaire */
        
    }
}