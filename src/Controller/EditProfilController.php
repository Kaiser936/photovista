<?php

namespace App\Controller;

use App\Form\InscriptionType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EditProfilController extends AbstractController
{
    /**
     * @Route("/editprofil/{id}", name="app_edit_profil")
     */
    public function index(EntityManagerInterface $manager, Request $request): Response
    {
        $user = $this->getUser();
        /** On obtient l'utilisateur actuellement connecté */
    
        $form = $this->createForm(InscriptionType::class, $user);
        /** On crée un formulaire d'inscription en utilisant la classe InscriptionType et on associe l'utilisateur actuel aux données du formulaire */
    
        $form->handleRequest($request);
        /** On traite la soumission du formulaire en fonction de la requête HTTP reçue */
    
        if ($form->isSubmitted()) {
            /** On vérifie si le formulaire a été soumis */
    
            $manager->persist($user);
            $manager->flush();
            /** On persiste (enregistre) les modifications de l'utilisateur dans la base de données */
    
            return $this->redirectToRoute('app_profil', ['id' => $user]);
            /** On redirige l'utilisateur vers la page de profil après avoir traité le formulaire avec succès */
        }
    
        return $this->render('edit_profil/index.html.twig', [
            'form' => $form->createView(),
        ]);
        /** Si le formulaire n'est pas soumis, on affiche la page d'édition de profil avec le formulaire pré-rempli avec les données de l'utilisateur actuel */
    }
}
