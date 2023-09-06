<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\InscriptionType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\FrameworkBundle\Controller\RedirectController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class InscriptionController extends AbstractController
{
    /**
     * @Route("/inscription", name="app_inscription")
     */
    public function index(Request $request, EntityManagerInterface $manager, UserPasswordHasherInterface $passwordHasher): Response {
    $user = new User();
    $form = $this->createForm(InscriptionType::class, $user);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        // Vérifiez si l'e-mail existe déjà dans la base de données
        $existingUser = $manager->getRepository(User::class)->findOneBy(['email' => $user->getEmail()]);

        if ($existingUser) {
            $this->addFlash('danger', 'Cet e-mail est déjà utilisé.');
        } else {
            // L'e-mail n'existe pas encore, vous pouvez créer l'utilisateur
            $hashedPassword = $passwordHasher->hashPassword($user, $user->getPassword());
            $user->setPassword($hashedPassword);

            $manager->persist($user);
            $manager->flush();

            $this->addFlash('success', 'L\'utilisateur a bien été créé');
            $form = $this->createForm(InscriptionType::class, new User());
            return $this->redirectToRoute('app_login');
        }
    }

    return $this->render('inscription/index.html.twig', [
        'form' => $form->createView(),
    ]);
}

}
