<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

use AppBundle\Form\Type\ConverterType;
use AppBundle\Form\Type\ConverterInverseType;

class ConverterController extends Controller
{
    /**
     * @Route("/convertisseur", name="converter")
     * @Method({"GET"})
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
