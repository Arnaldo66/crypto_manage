<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use AppBundle\Form\ConverterType;
use AppBundle\Form\ConverterInverseType;

class ConverterController extends Controller
{
    /**
     * @Route("/convertisseur", name="converter")
     */
    public function indexAction()
    {
        $form = $this->createForm(ConverterType::class);
        $form_inverse = $this->createForm(ConverterInverseType::class);
        return $this->render(':Converter:index.html.twig', array(
          'form' => $form->createView(), 'form_inverse' => $form_inverse->createView()
        ));
    }

}
