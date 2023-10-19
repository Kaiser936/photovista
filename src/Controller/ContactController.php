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
    /**
     * @Route("/contact", name="contact")
     */
    public function contact(Request $request): Response
    {
        $form = $this->createForm(ContactType::class);
        /** On crée un formulaire en utilisant la classe ContactType */

        $form->handleRequest($request);
        /** On traite la soumission du formulaire en fonction de la requête HTTP reçue */
    
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $nom = $data['nom'];
            $email = $data['email'];
            $messageText = $data['message'];
            /** On récupère les données soumises dans le formulaire */

            $contenu = "De: $nom\n\nEmail: $email\n\nMessage: $messageText";
            /** On crée le contenu de l'e-mail en utilisant les données du formulaire */

            $mail = new PHPMailer(true);
            $mail->isSMTP();
            /** On crée une instance de PHPMailer et on configure les paramètres SMTP */

            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;
            $mail->SMTPAuth = true;
            $mail->Username = 'soufiane.mohamad@gmail.com';
            $mail->Password = 'qrvowxepcdkwvrcm';
            $mail->setFrom('photovista@gmail.com', $nom);
            $mail->addAddress('soufiane.mohamad@gmail.com', 'Soufiane Mohamad');
            /** On configure les paramètres SMTP et les destinataires de l'e-mail */
    
            $mail->Subject = "PhotoVista - Nouveau message de $nom";
            $mail->Body = $contenu;
            /** On configure le sujet et le contenu de l'e-mail */
    
            if ($mail->send()) {
                $this->addFlash('success', 'Votre message a bien été envoyé !');
            } else {
                $this->addFlash('error', 'Une erreur s\'est produite lors de l\'envoi du message.');
            }
            /** On envoie l'e-mail et affiche un message de succès ou d'erreur en fonction du résultat */
    
            return $this->redirectToRoute('app_contact');
            /** On redirige l'utilisateur vers la page de contact après avoir traité le formulaire avec succès */
        }
    
        return $this->render('contact/index.html.twig', [
            'form' => $form->createView(),
        ]);
        /** Si le formulaire n'est pas soumis ou s'il n'est pas valide, on affiche la page de contact avec le formulaire */
    }
    
}
