<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use PHPMailer\PHPMailer\PHPMailer;
use App\Form\ContactType;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'contact')]
    public function contact(Request $request): Response
    {
        // Créez le formulaire de contact en utilisant le ContactType
        $form = $this->createForm(ContactType::class);

        // Gérez la soumission du formulaire
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Récupérez les données du formulaire
            $data = $form->getData();
            $nom = $data['nom'];
            $email = $data['email'];
            $messageText = $data['message'];

            // Créez le contenu de l'e-mail
            $contenu = "De: $nom \n\nEmail: $email\n\nMessage: $messageText";

            // Créez une instance de PHPMailer
            $mail = new PHPMailer(true);
            $mail->isSMTP();
            // Configurer les paramètres SMTP
            $mail->Host = 'smtp.gmail.com';
            $mail->Port = 465;
            $mail->SMTPSecure = 'ssl';
            $mail->SMTPAuth = true;
            $mail->Username = 'soufiane.mohamad@gmail.com';
            $mail->Password = 'qrvowxepcdkwvrcm';
            $mail->setFrom('soufiane.mohamad@gmail.com', $nom);
            $mail->addAddress('soufiane.mohamad@gmail.com', 'Soufiane Mohamad');
            // Configurer le sujet et le contenu de l'e-mail
            $mail->Subject = "Nouveau message de $nom";
            $mail->Body = $contenu;

            if ($mail->send()) {
                // Message de succès

                $this->addFlash('success', 'Votre message a bien été envoyé !');
            } else {
                // Message d'erreur
                $this->addFlash('error', 'Une erreur s\'est produite lors de l\'envoi du message.');
            }
        }

        // Affichez la page de contact avec le formulaire
        return $this->render('contact/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}