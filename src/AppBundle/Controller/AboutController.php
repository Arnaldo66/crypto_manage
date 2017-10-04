<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class AboutController extends Controller
{
    /**
     * @Route("/about", name="about")
     */
    public function indexAction()
    {
        return $this->render(':About:index.html.twig');
    }

    /**
     * @Route("/regle-du-jeu", name="rules")
     */
    public function regleAction()
    {
        return $this->render(':About:regle.html.twig');
    }

    /**
     * @Route("/qui-sommes-nous", name="who_i_am")
     */
    public function whoIAmAction()
    {
        return $this->render(':About:whoiam.html.twig');
    }
}
