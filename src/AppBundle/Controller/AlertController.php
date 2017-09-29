<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class AlertController extends Controller
{
    /**
     * @Route("/user/alerts", name="alert")
     */
    public function indexAction()
    {
        return $this->render(':Alert:index.html.twig', array(
            // ...
        ));
    }

    /**
     * @Route("/user/alert/new", name="alert_new")
     */
    public function newAction()
    {
        return $this->render(':Alert:new.html.twig', array(
            // ...
        ));
    }

    /**
     * @Route("/user/alert/delete", name="alert_delete")
     */
    public function deleteAction()
    {
        return $this->render(':Alert:delete.html.twig', array(
            // ...
        ));
    }

}