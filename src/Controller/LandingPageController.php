<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;

class LandingPageController extends Controller
{
    /**
     * @Route("", name="landing-page")
     * @Method({"GET"})
     */
    public function indexAction()
    {
        $currencies = $this->getDoctrine()
          ->getRepository('App:Currency')
          ->findBy([], array('rank'=>'ASC'), 10);

        return $this->render('LandingPage/index.html.twig', array('currencies'=>$currencies));
    }
}
