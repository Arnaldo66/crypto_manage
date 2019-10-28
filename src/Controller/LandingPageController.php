<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class LandingPageController extends AbstractController
{
    /**
     * @Route("", name="landing-page", methods={"GET"})
     */
    public function indexAction()
    {
        $currencies = $this->getDoctrine()
          ->getRepository('App:Currency')
          ->findBy([], array('rank'=>'ASC'), 10);

        return $this->render('LandingPage/index.html.twig', array('currencies'=>$currencies));
    }
}
