<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class RedirectionController extends Controller
{
    /**
     * @Route("/a-propos", name="about")
     * @Method({"GET"})
     */
    public function indexAction()
    {
        return $this->redirectToRoute('article_show', array(
          'slug' => 'a-propos'
        ), 301);
    }

    /**
     * @Route("/premiers-pas", name="first_step")
     * @Method({"GET"})
     */
    public function firstStepAction()
    {
      return $this->redirectToRoute('article_show', array(
        'slug' => 'explication-du-jeu-de-simulation-de-trading'
      ), 301);
    }

    /**
     * @Route("/ou-acheter-des-bitcoins", name="where_buy")
     * @Method({"GET"})
     */
    public function whereBuyAction()
    {
      return $this->redirectToRoute('article_show', array(
        'slug' => 'ou-acheter-vendre-des-bitcoins'
      ), 301);
    }
}
