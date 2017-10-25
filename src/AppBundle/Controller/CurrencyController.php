<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use AppBundle\Entity\Currency;

class CurrencyController extends Controller
{
    /**
     * @Route("/crypto-monnaies/{slug}", name="currency_show")
     * @ParamConverter("currency", class="AppBundle:Currency")
     */
    public function showAction(Currency $currency)
    {

        $data = $this->formatDataGraph(
                      $this->getDoctrine()->getManager()
                      ->getRepository('AppBundle:Currency')
                      ->getDataLastMonth($currency)
                );
        return $this->render(':Currency:show.html.twig', array(
          'currency' => $currency, 'data' => $data
        ));
    }

    /**
     * format data for morris.js graph
     */
     private function formatDataGraph($data){
       return json_encode($data);
     }

}
