<?php

namespace App\Controller;

use DateTime;
use App\Entity\Post;
use App\Form\PostType;
use App\Repository\UserRepository;
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
     */
    public function index(Request $request, EntityManagerInterface $manager, SluggerInterface $slugger): Response
    {
        $user = $this->getUser();
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $picture = $form->get('picture')->getData();

            if ($picture) {
                $originalFilename = pathinfo($picture->getClientOriginalName(), PATHINFO_FILENAME);

                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $picture->guessExtension();

                try {
                    $picture->move(
                        $this->getParameter('pictures_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // Handle the exception if necessary
                }

                $post->setPicture($newFilename);
            }

            $currentDateTime = new DateTime('now');
            $post->setCreatedAt($currentDateTime);
            $post->setUpdatedAt($currentDateTime);
            $post->setCreated_by($user);

            $manager->persist($post);
            $manager->flush();

            $this->addFlash('success', 'Le livre a bien été ajouté');

            $form = $this->createForm(PostType::class, new Post());

            return $this->redirectToRoute('app_publication');
        }

        return $this->render('add_post/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
