<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;


class HomeController extends Controller
{
    /**
     * @Route("", name="home")
     */
    public function indexAction(Request $request)
    {
        $currencyMomentValues = $this->getDoctrine()
          ->getRepository('AppBundle:CurrencyValueMoment')
          ->findBy([],[],100);

        return $this->render(':Home:index.html.twig',array('currencyMomentValues'=>$currencyMomentValues));
    }

}
