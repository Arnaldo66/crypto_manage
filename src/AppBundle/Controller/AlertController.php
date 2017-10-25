<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class AlertController extends Controller
{
    /**
     * @Route("/u/alerts", name="alert")
     */
    public function alertsAction()
    {
        $alerts = $this->getDoctrine()->getManager()
                  ->getRepository('AppBundle:Alert')
                  ->findBy(array('user'=>$this->getUser()));
        return $this->render(':Alert:alerts.html.twig', array(
            'alerts' => $alerts
        ));
    }

    /**
     * @Route("/u/alerts/new", name="alert_new")
     */
    public function newAction()
    {
        die('inew');
        return $this->render(':Alert:new.html.twig', array(
            // ...
        ));
    }

    /**
     * @Route("/u/alerts/delete", name="alert_delete")
     */
    public function deleteAction()
    {
        return $this->render(':Alert:delete.html.twig', array(
            // ...
        ));
    }

}
