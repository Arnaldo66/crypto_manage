<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class HomeController extends Controller
{
    /**
     * @Route("", name="home")
     */
    public function indexAction()
    {
        $currencyMomentValues = $this->getDoctrine()
          ->getRepository('AppBundle:CurrencyValueMoment')
          ->findBy([],[],100);

        return $this->render(':Home:index.html.twig',array('currencyMomentValues'=>$currencyMomentValues));
    }

}
