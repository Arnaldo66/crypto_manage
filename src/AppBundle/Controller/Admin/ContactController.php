<?php

namespace AppBundle\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class ContactController extends Controller
{
    /**
     * @Route("/egbo/contacts", name="admin_contacts")
     */
    public function contactsAction()
    {
        $contacts = $this->getDoctrine()
                          ->getRepository('AppBundle:Contact')
                          ->findAll();

        return $this->render(':Admin\Contact:contacts.html.twig', array(
            'contacts' => $contacts
        ));
    }

}
