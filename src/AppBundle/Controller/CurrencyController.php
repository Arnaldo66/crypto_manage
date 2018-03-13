<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use AppBundle\Entity\Currency;

class CurrencyController extends Controller
{

    /**
     * @Route("/toutes-les-crypto-monnaies", name="currencies")
     * @Method({"GET"})
     */
    public function currenciesAction()
    {
        $currencies = $this->getDoctrine()->getManager()
                          ->getRepository('AppBundle:Currency')
                          ->findAll();
        return $this->render(':Currency:currencies.html.twig', array(
            'currencies' => $currencies, 'dashboard' => 0
        ));
    }

    /**
     * @Route("/crypto-monnaies/{slug}", name="currency_show")
     * @Method({"GET"})
     * @ParamConverter("currency", class="AppBundle:Currency")
     */
    public function showAction(Currency $currency)
    {
        return $this->render(':Currency:show.html.twig', array(
          'currency' => $currency
        ));
    }
}
