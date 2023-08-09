<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\InscriptionType;
use App\Form\PostType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\User;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EditProfilController extends AbstractController
{
    /**
     * @Route("/editprofil/{id}", name="app_edit_profil")
     */
    public function index($id, EntityManagerInterface $manager, Request $request): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(InscriptionType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $manager->persist($user);
            $manager->flush();
            return $this->redirectToRoute('app_profil', ['id' => $user->getId()]);
        }

        return $this->render('edit_profil/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
