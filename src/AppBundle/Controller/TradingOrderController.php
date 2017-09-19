<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;

use AppBundle\Form\TradingOrderFirstStepType;
use AppBundle\Form\TradingOrderNextStepType;
use AppBundle\Entity\TradingOrder;

class TradingOrderController extends Controller
{
    /**
     * @Route("/user/trade/order/new", name="trade_order_new")
     */
    public function newAction()
    {
        $tradeOrder = new TradingOrder;
        //
        $form = $this->createForm(TradingOrderFirstStepType::class, $tradeOrder, array(
          'action' => $this->generateUrl('trade_order_new_next_step'),
          'method' => 'POST',
        ));

        return $this->render(':TradingOrder:new-first-step.html.twig', array(
          'form'=> $form->createView()
        ));
    }

    /**
     * @Route("/user/trade/order/new/next-step", name="trade_order_new_next_step")
     * @Method({"POST"})
     */
    public function newNextStepAction(Request $request)
    {
        $tradeOrder = new TradingOrder;
        $form = $this->createForm(TradingOrderFirstStepType::class, $tradeOrder);
        $form->handleRequest($request);
        if ($form->isSubmitted()){
          $currency = $tradeOrder->getCurrency();
          $form = $this->createForm(TradingOrderNextStepType::class, $tradeOrder, array('user' => $this->getUser()));
          return $this->render(':TradingOrder:new-next-step.html.twig', array(
            'form'=> $form->createView(), 'currency' => $currency
          ));
        }

    }

}
