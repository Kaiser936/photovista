<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\InscriptionType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class InscriptionController extends AbstractController
{
    /**
     * @Route("/inscription", name="app_inscription")
     */
    public function index(Request $request, EntityManagerInterface $manager, UserPasswordHasherInterface $passwordHasher): Response {
        $user = new User();
        /** On crée une nouvelle instance de la classe User pour représenter l'utilisateur */
    
        $form = $this->createForm(InscriptionType::class, $user);
        /** On crée un formulaire d'inscription en utilisant la classe InscriptionType et on associe l'objet $user aux données du formulaire */
    
        $form->handleRequest($request);
        /** On traite la soumission du formulaire en fonction de la requête HTTP reçue */
    
        if ($form->isSubmitted() && $form->isValid()) {
            /** On vérifie si le formulaire a été soumis et si les données sont valides */

            $existingUser = $manager->getRepository(User::class)->findOneBy(['email' => $user->getEmail()]);
            /** On vérifie si un utilisateur avec le même e-mail existe déjà dans la base de données */
    
            if ($existingUser) {
                $this->addFlash('danger', 'Cet e-mail est déjà utilisé.');
                /** Si l'e-mail existe déjà, on affiche un message d'erreur */
            } else {

                $hashedPassword = $passwordHasher->hashPassword($user, $user->getPassword());
                $user->setPassword($hashedPassword);
                /** On hache le mot de passe de l'utilisateur pour le stocker de manière sécurisée dans la base de données */
    
                $manager->persist($user);
                $manager->flush();
                /** On persiste (enregistre) le nouvel utilisateur dans la base de données */
    
                $this->addFlash('success', 'L\'utilisateur a bien été créé');
                /** On affiche un message de succès */
    
                $form = $this->createForm(InscriptionType::class, new User());
                /** On réinitialise le formulaire avec une nouvelle instance de la classe User */
    
                return $this->redirectToRoute('app_login');
                /** On redirige l'utilisateur vers la page de connexion après avoir créé l'utilisateur avec succès */
            }
        }
    
        return $this->render('inscription/index.html.twig', [
            'form' => $form->createView(),
        ]);
        /** Si le formulaire n'est pas soumis ou s'il n'est pas valide, on affiche la page d'inscription avec le formulaire */
    }
    

}
