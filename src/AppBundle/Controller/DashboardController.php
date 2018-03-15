<?php

namespace AppBundle\Controller;

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
          ->getRepository('AppBundle:Currency')
          ->findBy([], array('rank'=>'ASC'), 100);
        return $this->render(':Currency:currencies.html.twig', array(
            'currencies' => $currencies, 'dashboard' => 1
        ));
    }
}
