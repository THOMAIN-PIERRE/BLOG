<?php

namespace App\Controller;

use App\Entity\ContactType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    /**
     * @Route("/contact", name="contact")
     */
    public function index(Request $request, MailerInterface $mailer)
    {
        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $contact = $form->getData();

            //ici, nous envoyons le mail
            // dd($contact);
            $message = new Email();
            //On attribue l'expéditeur
            $message->From($contact["email"])
            //On attribue le destinataire
            ->To("pierre.thomain64400@gmail.com")
            //On créé le message avec la vue Twig
              ->html(
                  $this->renderView(
                      "emails/contact.html.twig", compact('contact')

                  ),
                  'text/html'
              )
        ;
            
              
        //On envoie le message      
        $mailer->send($message);  
        
        $this->addFlash('message', 'Votre email a bien été envoyé');
        // return $this->redirectToRoute('main');
        }

        //The controller must return a "Symfony\Component\HttpFoundation\Response" object but it returned null. Did you forget to add a return statement somewhere in your controller?
        return $this->render('contact/index.html.twig', [
            'contactForm' => $form->createview()
        ]); 
    }
}