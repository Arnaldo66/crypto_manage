<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;

use AppBundle\Form\TradingOrderFirstStepType;
use AppBundle\Form\TradingOrderNextStepType;
use AppBundle\Entity\TradingOrder;
use AppBundle\Service\WalletManager;

class TradingOrderController extends Controller
{
    /**
     * @Route("/u/trade/order/new", name="trade_order_new")
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
     * @Route("/u/trade/order/new/next-step", name="trade_order_new_next_step")
     * @Method({"POST"})
     */
    public function newNextStepAction(Request $request)
    {
        $tradeOrder = new TradingOrder;
        $form = $this->createForm(TradingOrderFirstStepType::class, $tradeOrder);
        $form->handleRequest($request);

        $currency = $tradeOrder->getCurrency();
        if($currency === NULL){
          $this->addFlash('error-message','Veuillez renseigner une crypto-monnaie');
          return $this->redirectToRoute('trade_order_new');
        }

        if ($form->isSubmitted()){
          $em = $this->getDoctrine()->getManager();
          $orderAction = $em->getRepository('AppBundle:OrderAction')->find(1);
          $orderMethod = $em->getRepository('AppBundle:OrderMethod')->find(1);
          $tradeOrder->setOrderAction($orderAction);
          $tradeOrder->setOrderMethod($orderMethod);

          $form = $this->createForm(TradingOrderNextStepType::class, $tradeOrder, array(
            'user' => $this->getUser(),
            'action' => $this->generateUrl('trade_order_new_final_step'),
            'method' => 'POST',
          ));
          return $this->render(':TradingOrder:new-next-step.html.twig', array(
            'form'=> $form->createView(), 'currency' => $currency
          ));
        }
    }

    /**
     * @Route("/u/trade/order/new/final-step", name="trade_order_new_final_step")
     * @Method({"POST"})
     */
    public function newFinalStepAction(Request $request, WalletManager $walletManager)
    {
      $tradeOrder = new TradingOrder;

      $form = $this->createForm(TradingOrderNextStepType::class, $tradeOrder, array('user' => $this->getUser()));
      $form->handleRequest($request);

      if ($form->isSubmitted() && $form->isValid()){
        $em = $this->getDoctrine()->getManager();
        if($walletManager->canFinaliseOrder($tradeOrder,$em)['success']){
          $walletManager->finaliseOrder($tradeOrder,$em);

          $this->addFlash('success-message','L\'ordre a bien été enregistré');
          return $this->redirectToRoute('trade_show', array('id'=>$tradeOrder->getTradingWallet()->getId()));
        }
      }
      return $this->render(':TradingOrder:new-next-step.html.twig', array(
        'form'=> $form->createView(), 'currency' => $tradeOrder->getCurrency()
      ));
    }
}
