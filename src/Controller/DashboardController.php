<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;

class DashboardController extends Controller
{
    /**
     * @Route("/tableau-de-bord", name="dashboard")
     * @Method({"GET"})
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
     * @Route("/.well-known/brave-rewards-verification.txt", name="braveidentification")
     * @Method({"GET"})
     */
    public function braveAction()
    {
        return $this->render(':Brave:index.html.twig');
    }
}
