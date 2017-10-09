<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

use AppBundle\Form\ContactType;
use AppBundle\Entity\Contact;

class ContactController extends Controller
{
    /**
     * @Route("/contact", name="contact")
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
          $this->addFlash('success-message','Votre message a bien été envoyé');
          return $this->redirectToRoute('contact');
        }
        return $this->render(':Contact:new.html.twig', array(
          'form'=>$form->createView()
        ));
    }

    /**
     * send contact email
     */
     private function sendContactEmail($contact){
       $mailer = $this->get('mailer');
       $message = (new \Swift_Message('Hello Email'))
        ->setFrom($contact->getEmail())
        ->setTo('admin@e-goldenboy.com')
        ->setBody(
            $contact->getMessage(),
            'text/html'
        );
        $mailer->send($message);
     }
}
