<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class AboutController extends Controller
{
    /**
     * @Route("/a-propos", name="about")
     */
    public function indexAction()
    {
        return $this->render(':About:index.html.twig');
    }

    /**
     * @Route("/premiers-pas", name="first_step")
     */
    public function firstStepAction()
    {
        return $this->render(':About:first-step.html.twig');
    }

    /**
     * @Route("/ou-acheter-des-bitcoins", name="where_buy")
     */
    public function whereBuyAction()
    {
        return $this->render(':About:where-buy.html.twig');
    }
}
