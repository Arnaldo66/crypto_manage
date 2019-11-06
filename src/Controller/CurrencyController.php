<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use App\Entity\Currency;

class CurrencyController extends AbstractController
{

    /**
     * @Route("/toutes-les-crypto-monnaies", name="currencies", methods={"GET"})
     */
    public function currenciesAction()
    {
        $currencies = $this->getDoctrine()->getManager()
                          ->getRepository('App:Currency')
                          ->findBy([], array('rank'=>'ASC'), 1000);
        return $this->render('Currency/currencies.html.twig', array(
            'currencies' => $currencies, 'dashboard' => 0
        ));
    }

    /**
     * @Route("/crypto-monnaies/{slug}", name="currency_show", methods={"GET"})
     * @ParamConverter("currency", class="App:Currency")
     */
    public function showAction(Currency $currency)
    {
        return $this->render('Currency/show.html.twig', array(
          'currency' => $currency
        ));
    }
}
