<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;

use AppBundle\Form\TradingOrderFirstStepType;
use AppBundle\Form\TradingOrderNextStepType;
use AppBundle\Entity\TradingOrder;
use AppBundle\Entity\CurrencyWallet;

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
          $em = $this->getDoctrine()->getManager();
          $orderAction = $em->getRepository('AppBundle:OrderAction')->find(1);
          $orderMethod = $em->getRepository('AppBundle:OrderMethod')->find(1);
          $currency = $tradeOrder->getCurrency();
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
     * @Route("/user/trade/order/new/final-step", name="trade_order_new_final_step")
     * @Method({"POST"})
     */
    public function newFinalStepAction(Request $request)
    {

      die('youhou');
      $tradeOrder = new TradingOrder;

      $form = $this->createForm(TradingOrderNextStepType::class, $tradeOrder, array('user' => $this->getUser()));
      $form->handleRequest($request);

      if ($form->isSubmitted() && $form->isValid()){
        if($this->canFinaliseOrder($tradeOrder)['success']){
          //TODO: if pending do something else
          $this->finaliseOrder($tradeOrder);
        }
      }
      return $this->render(':TradingOrder:new-next-step.html.twig', array(
        'form'=> $form->createView(), 'currency' => $tradeOrder->getCurrency()
      ));
    }

    /**
     * check creation
     */
     private function canFinaliseOrder($tradeOrder){
       //TODO: symfony workflow state machine
       //TODO: check real currency and not amout if he change with js && change total with this value
       $em = $this->getDoctrine()->getManager();
       if($tradeOrder->getOrderAction()->getId() == $this->container->getParameter('order_buy')){
         // achat: total >= montant euro wallet && order pending ?
         $totalOrderPending = $this->getTotalWAlletPending($tradeOrder->getTradingWallet(),$em);
         $totalWallet = $tradeOrder->getTradingWallet()->getEuroWallet()->getAmount() - $totalOrderPending;
         if($totalWallet < $tradeOrder->getTotal()){
           return Array("success"=>false, "message"=>"Vous n'avez pas les fonds nécessaires");
         }
       }else{
         // vente: exists wallet && amount >= trader order amount
         $wallet = $em->getRepository("AppBundle:CurrencyWallet")->findOneBy(array(
           'tradingWallet' => $tradeOrder->getTradingWallet(),
           'currency' => $tradeOrder->getCurrency()
         ));
         $amoutWallet = $wallet->getAmount() - $this->getAmountCurrencyWalletPending($tradeOrder,$em);
         if($wallet === NULL || $amoutWallet < $tradeOrder->getAmount()){
           return Array("success"=>false, "message"=>"Vous n'avez pas les fonds nécessaires");
         }
       }

       return Array("success"=>true, "message"=>"OK");
     }


     /**
      * get total amoutn all order pending for this wallet
      **/
      private function getTotalWAlletPending($tradingWallet,$em){
        $total = 0;
        $orders = $em->getRepository('AppBundle:TradingOrder')->findBy(array(
          'tradingWallet' => $tradingWallet,
          'orderStatus' => $this->container->getParameter('order_status_pending'),
          'orderAction' => $this->container->getParameter('order_buy')
        ));
        foreach ($orders as $order) {
          $total += $order->getTotal();
        }

        return $total;
      }

      /**
       *
       */
       private function getAmountCurrencyWalletPending($tradeOrder,$em){
         $amount = 0;
         $orders = $em->getRepository('AppBundle:TradingOrder')->findBy(array(
           'tradingWallet' => $tradeOrder->getTradingWallet(),
           'orderStatus' => $this->container->getParameter('order_status_pending'),
           'orderAction' => $this->container->getParameter('order_sell'),
           'currency' => $tradeOrder->getCurrency()
         ));
         foreach ($orders as $order) {
           $amount += $order->getAmount();
         }
         return $amount;
       }

     /**
      * finalise new order
      */
      public function finaliseOrder($tradeOrder){
        $em = $this->getDoctrine()->getManager();
        $tradeOrder->setOrderStatus($this->getStatus($tradeOrder,$em));
        $total = $this->calculateTotal($tradeOrder);
        $tradeOrder->setTotal($total);
        if($tradeOrder->getOrderAction()->getId() == $this->container->getParameter('order_buy')){
          $wallet = $this->incrementeCurrencyWallet($tradeOrder,$em);
          $this->decrementeEuroWallet($tradeOrder, $total);
        }else{
          $wallet = $this->decrementeCurrencyWallet($tradeOrder,$em);
          $this->incrementeEuroWallet($tradeOrder, $total);
        }


        $em->persist($tradeOrder);
        $em->persist($wallet);

        $em->flush();
      }

      /**
       * get good status
       */
       private function getStatus($tradeOrder, $em){
         if($tradeOrder->getOrderMethod()->getId() == $this->container->getParameter('order_market')){
           $status = $this->container->getParameter('order_status_ok');
         }else{
           $status = $this->container->getParameter('order_status_pending');
         }
         return $em->getRepository("AppBundle:OrderStatus")->find($status);
       }

       /**
        * calculate total
        */
        private function calculateTotal($tradeOrder){
          //TODO: puts usd value && euro value in currency entity
          $priceEur = $tradeOrder->getCurrency()->getCurrencyValueMoment()->getPriceEur();
          return $priceEur * $tradeOrder->getAmount();
        }

        /**
         * incrémente wallet
         */
         private function incrementeCurrencyWallet($tradeOrder,$em){
           //check if exists ? create : update
           $wallet = $em->getRepository("AppBundle:CurrencyWallet")->findOneBy(array(
             'tradingWallet' => $tradeOrder->getTradingWallet(),
             'currency' => $tradeOrder->getCurrency()
           ));
           if($wallet === NULL){
             $wallet = new CurrencyWallet();
             $wallet->setCurrency($tradeOrder->getCurrency());
             $wallet->setTradingWallet($tradeOrder->getTradingWallet());
             $wallet->setAmount($tradeOrder->getAmount());
           }else{
             $wallet->setAmount($tradeOrder->getAmount() + $wallet->getAmount());
           }

           return $wallet;
         }

         /**
          * decremente euro wallet
          */
          private function decrementeEuroWallet($tradeOrder, $total){
            $euroWallet = $tradeOrder->getTradingWallet()->getEuroWallet();
            $euroWallet->setAmount($euroWallet->getAmount() - $total);
          }

        /**
         * decremente wallet
         */
         private function decrementeCurrencyWallet($tradeOrder,$em){
           $wallet = $em->getRepository("AppBundle:CurrencyWallet")->findOneBy(array(
             'tradingWallet' => $tradeOrder->getTradingWallet(),
             'currency' => $tradeOrder->getCurrency()
           ));
           $wallet->setAmount($wallet->getAmount() - $tradeOrder->getAmount());

           return $wallet;
         }

         /**
          * incremente euro wallet
          */
          private function incrementeEuroWallet($tradeOrder, $total){
            $euroWallet = $tradeOrder->getTradingWallet()->getEuroWallet();
            $euroWallet->setAmount($euroWallet->getAmount() + $total);
          }

}
