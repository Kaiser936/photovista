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
        $post->setPicture("");
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            
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



            $post->setUpdatedAt(new \DateTime());
            $manager->persist($post);
            $manager->flush();
            return $this->redirectToRoute('app_single_post', ['id' => $post->getId()]);
        }




        return $this->render('edit_post/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}