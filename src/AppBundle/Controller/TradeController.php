<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

use AppBundle\Form\TradingWalletType;
use AppBundle\Entity\TradingWallet;
use AppBundle\Entity\EuroWallet;

class TradeController extends Controller
{
    /**
     * @Route("/user/trade/new", name="trade_new")
     */
    public function newAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $tradingWallet = new TradingWallet();
        $tradingWallet->setPublic(true);
        $tradingWallet->setUser($this->getUser());
        $tradingWallet->setEuroWallet($this->createEuroWallet($em,$tradingWallet));

        $form = $this->createForm(TradingWalletType::class, $tradingWallet);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
          $em->persist($tradingWallet);
          $em->flush();
        }

        return $this->render(':Trade:new.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * Create a new euro wallet by default
     */
     private function createEuroWallet($em,$tradingWallet){
       $euroWallet = new EuroWallet;
       $euroWallet->setAmount(15000.00);
       $euroWallet->setTradingWallet($tradingWallet);
       $em->persist($euroWallet);
       return $euroWallet;
     }

}
