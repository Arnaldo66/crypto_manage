<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use AppBundle\Entity\Currency;

class CurrencyController extends Controller
{
    /**
     * @Route("/currencies/{slug}", name="currency_show")
     * @ParamConverter("currency", class="AppBundle:Currency")
     */
    public function showAction(Currency $currency)
    {
        return $this->render(':Currency:show.html.twig', array(
          'currency' => $currency
        ));
    }

}
