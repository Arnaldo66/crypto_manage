<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class HomeController extends Controller
{
    /**
     * @Route("")
     */
    public function indexAction()
    {
        $currencyMomentValues = $this->getDoctrine()->getRepository('AppBundle:CurrencyValueMoment')->findAll();

        return $this->render(':home:index.html.twig',array('currencyMomentValues'=>$currencyMomentValues));
    }

}
