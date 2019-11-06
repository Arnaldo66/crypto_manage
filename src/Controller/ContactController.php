<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

use App\Form\ContactType;
use App\Entity\Contact;

class ContactController extends AbstractController
{

    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    public function __construct(\Swift_Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * @Route("/contact", name="contact", methods={"GET|POST"})
     */
    public function newAction(Request $request)
    {
        $contact = new Contact();

        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($contact);
            $em->flush();

            $this->sendContactEmail($contact);
            $this->addFlash('success-message', 'Votre message a bien été envoyé');
            return $this->redirectToRoute('contact');
        }
        return $this->render('Contact/new.html.twig', array(
          'form'=>$form->createView()
        ));
    }

    /**
     * send contact email
     */
    private function sendContactEmail($contact)
    {
        $message = (new \Swift_Message('Contact form email'))
        ->setFrom($contact->getEmail())
        ->setTo('admin@e-goldenboy.com')
        ->setBody(
            $contact->getMessage(),
            'text/html'
        );
        $this->mailer->send($message);
    }
}
