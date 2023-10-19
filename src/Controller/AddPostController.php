<?php

namespace App\Controller;

use DateTime;
use App\Entity\Post;
use App\Form\PostType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class AddPostController extends AbstractController
{
    /**
     * @Route("/add/post", name="app_add_post")
     * 
     */
    public function index(Request $request, EntityManagerInterface $manager, SluggerInterface $slugger): Response
    {
        $user = $this->getUser(); /** On récupere l'utilisateur connecté */
        $post = new Post(); /** On crée une nouvelle instance de la classe Post pour créer une nouvelle publications */
        $form = $this->createForm(PostType::class, $post); /** On crée un formulaire en utilisant la classe PostType et on associe l'objet $post aux données du formulaire */
        $form->handleRequest($request); /** On traite la soumission du formulaire en fonction de la requête HTTP reçue (généralement une requête POST) */

        if ($form->isSubmitted() && $form->isValid()) {
            /** On vérifie si le formulaire a été soumis et si les données sont valides */
        
            $picture = $form->get('picture')->getData();
        
            if ($picture) {
                /** On vérifie si une image a été téléchargée via le formulaire */
        
                $originalFilename = pathinfo($picture->getClientOriginalName(), PATHINFO_FILENAME);
                /** On obtient le nom de fichier d'origine de l'image téléchargée (sans l'extension) */
                
                $safeFilename = $slugger->slug($originalFilename);
                /** On utilise un "slugger" pour générer un nom de fichier "sûr" en remplaçant les caractères spéciaux et en mettant en minuscules */
                
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $picture->guessExtension();
                /** On génère un nouveau nom de fichier en ajoutant un identifiant unique et en conservant l'extension d'origine */
        
                try {
                    /** On génère un nom de fichier unique pour l'image téléchargée */
                    /** On tente de déplacer l'image téléchargée vers le répertoire spécifié */
        
                    $picture->move(
                        $this->getParameter('pictures_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // En cas d'exception, on gère l'erreur ici si nécessaire
                }
        
                $post->setPicture($newFilename);
            }
        
            $currentDateTime = new DateTime('now');
            /** On obtient la date et l'heure actuelles */
        
            $post->setCreatedAt($currentDateTime);
            $post->setUpdatedAt($currentDateTime);
            $post->setCreated_by($user);
            /** On met à jour les propriétés de l'objet $post avec les nouvelles données */
        
            $manager->persist($post);
            $manager->flush();
            /** On persiste (enregistre) l'objet $post dans la base de données */
        
            $form = $this->createForm(PostType::class, new Post());
            /** On réinitialise le formulaire avec une nouvelle instance de la classe Post */
        
            return $this->redirectToRoute('app_profil');
            /** On redirige l'utilisateur vers une autre page après le succès du traitement du formulaire */
        }
        
        return $this->render('add_post/index.html.twig', [
            'form' => $form->createView(),
        ]);
        /** Si le formulaire n'est pas soumis ou s'il n'est pas valide, on affiche la page d'ajout de publication avec le formulaire */
        
    }
}
