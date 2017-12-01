<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;

class DashboardController extends Controller
{
    /**
     * @Route("/tableau-de-bord", name="dashboard")
     * @Method({"GET"})
     */
    public function indexAction()
    {
        return $this->render(':Dashboard:index.html.twig', array(
            // ...
        ));
    }

}
