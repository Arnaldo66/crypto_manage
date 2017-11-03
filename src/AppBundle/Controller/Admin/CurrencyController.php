<?php

namespace AppBundle\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class CurrencyController extends Controller
{
    /**
     * @Route("/egbo/currencies", name="admin_currencies")
     * @Method({"GET"})
     */
    public function currenciesAction()
    {
        $currencies = $this->getDoctrine()->getManager()
                      ->getRepository('AppBundle:Currency')
                      ->findAll();

        return $this->render(':Admin\Currency:currencies.html.twig', array(
            'currencies' => $currencies
        ));
    }

}
