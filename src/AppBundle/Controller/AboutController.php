<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class AboutController extends Controller
{
    /**
     * @Route("/a-propos", name="about")
     * @Method({"GET"})
     */
    public function indexAction()
    {
        return $this->render(':About:index.html.twig');
    }

    /**
     * @Route("/premiers-pas", name="first_step")
     * @Method({"GET"})
     */
    public function firstStepAction()
    {
        return $this->render(':About:first-step.html.twig');
    }

    /**
     * @Route("/ou-acheter-des-bitcoins", name="where_buy")
     * @Method({"GET"})
     */
    public function whereBuyAction()
    {
        return $this->render(':About:where-buy.html.twig');
    }
}
