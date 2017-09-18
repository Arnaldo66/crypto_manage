<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use AppBundle\Form\TradingWalletType;
use AppBundle\Entity\TradingWallet;

class TradeController extends Controller
{
    /**
     * @Route("/user/trade/new", name="trade_new")
     */
    public function newAction()
    {
        $tradingWallet = new TradingWallet();
        $form = $this->createForm(TradingWalletType::class, $tradingWallet);
        return $this->render(':Trade:new.html.twig', array(
            'form' => $form->createView()
        ));
    }

}
