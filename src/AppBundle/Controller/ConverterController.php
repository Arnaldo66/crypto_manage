<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class ConverterController extends Controller
{
    /**
     * @Route("/convertisseur", name="converter")
     */
    public function newAction()
    {
        return $this->render(':Converter:new.html.twig', array(
            // ...
        ));
    }

}
