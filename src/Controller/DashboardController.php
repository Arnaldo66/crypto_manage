<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    /**
     * @Route("/tableau-de-bord", name="dashboard", methods={"GET"})
     */
    public function indexAction()
    {
        $currencies = $this->getDoctrine()
          ->getRepository('App:Currency')
          ->findBy([], array('rank'=>'ASC'), 100);
        return $this->render('Currency/currencies.html.twig', array(
            'currencies' => $currencies, 'dashboard' => 1
        ));
    }

    /**
     * @Route("/.well-known/brave-rewards-verification.txt", name="braveidentification", methods={"GET"})
     */
    public function braveAction()
    {
        return $this->render(':Brave:index.html.twig');
    }
}
