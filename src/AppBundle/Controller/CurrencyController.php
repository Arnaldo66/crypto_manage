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
     public function currenciesAction(){
       $currencies = $this->getDoctrine()->getManager()
                          ->getRepository('AppBundle:Currency')
                          ->findAll();
      return $this->render(':Currency:currencies.html.twig', array(
        'currencies' => $currencies
      ));
     }

    /**
     * @Route("/crypto-monnaies/{slug}", name="currency_show")
     * @Method({"GET"})
     * @ParamConverter("currency", class="AppBundle:Currency")
     */
    public function showAction(Currency $currency)
    {
        $data = $this->getGraphData($currency);
        return $this->render(':Currency:show.html.twig', array(
          'currency' => $currency, 'data' => $data
        ));
    }

    /**
     * get date for year graph
     */
     private function getGraphData($currency){
       $date = new \DateTime();
       $em = $this->getDoctrine()->getManager();
       if($currency->getGraphYearDate() === NULL || $currency->getGraphYearDate() < $date){
         $result = $this->formatDataGraph(
                        $em->getRepository('AppBundle:Currency')
                           ->getDataLastMonth($currency)
                       );
         $currency->setGraphYearDate($date->add(new \DateInterval('P1D')));
         $currency->setGraphYearData($result);
         $em->flush();
       }else{
         $result = $currency->getGraphYearData();
       }
       return $result;

     }

    /**
     * format data for morris.js graph
     */
     private function formatDataGraph($data){
       return json_encode($data);
     }

}
