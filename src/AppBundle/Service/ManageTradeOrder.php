<?php
// src/AppBundle/Service/ManageTradeOrder.php
namespace AppBundle\Service;

class ManageTradeOrder
{
  /**
   * check creation
   */
   public function canFinaliseOrder($tradeOrder){
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
}
