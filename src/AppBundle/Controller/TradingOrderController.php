<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use AppBundle\Form\TradingOrderType;
use AppBundle\Entity\TradingOrder;

class TradingOrderController extends Controller
{
    /**
     * @Route("/user/trade/order/new", name="trade_order_new")
     */
    public function newAction()
    {
        $tradeOrder = new TradingOrder;
        $form = $this->createForm(TradingOrderType::class, $tradeOrder, array('user' => $this->getUser()));

        return $this->render(':TradingOrder:new.html.twig', array(
          'form'=> $form->createView()
        ));
    }

}
