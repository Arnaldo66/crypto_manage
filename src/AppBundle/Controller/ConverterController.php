<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use AppBundle\Form\ConverterType;

class ConverterController extends Controller
{
    /**
     * @Route("/convertisseur", name="converter")
     */
    public function newAction()
    {
        //TODO: limiter aux 100 meilleur device par rank
        $form = $this->createForm(ConverterType::class);
        return $this->render(':Converter:new.html.twig', array(
          'form' => $form->createView()
        ));
    }

}
