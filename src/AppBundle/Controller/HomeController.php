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
        $currencies = $this->getDoctrine()
          ->getRepository('AppBundle:Currency')
          ->findBy([],array('rank'=>'ASC'),100);

        return $this->render(':Home:index.html.twig',array('currencies'=>$currencies));
    }

}
