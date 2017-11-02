<?php
namespace AppBundle\Service;

use Symfony\Component\DependencyInjection\ContainerInterface;

use AppBundle\Entity\CurrencyWallet;
use AppBundle\Entity\TradingWallet;

class WalletManager
{
  private $container;

  //TODO: symfony workflow state machine
  //check real currency and not amout if he change with js && change total with this value
  //pass arg and not all the container service

  public function __construct(ContainerInterface $container){
    $this->container = $container;
  }

  /**
   * récupère le total de toutes les currency wallet
   **/
   public function getTotalCurrencyWalletValue(TradingWallet $tradingWallet){
     $wallets = $tradingWallet->getCurrencyWallets();
     $total = 0;

     if($wallets->count() === 0){
       return $total;
     }

     foreach ($tradingWallet->getCurrencyWallets() as $wallet) {
       $total += $wallet->getAmount() * $wallet->getCurrency()->getPriceEur();
     }
     return round($total, 2);
   }


  /**
  * check creation
  */
  public function canFinaliseOrder($tradeOrder,$em){
    if($tradeOrder->getOrderAction()->getId() == $this->container->getParameter('order_buy')){
      // achat: total >= montant euro wallet && order pending ?
      $totalOrderPending = $this->getTotalWalletPending($tradeOrder->getTradingWallet(),$em);
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
  private function getTotalWalletPending($tradingWallet,$em){
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
  public function finaliseOrder($tradeOrder,$em){
    $tradeOrder->setOrderStatus($this->getStatus($tradeOrder,$em));
    $total = $this->calculateTotal($tradeOrder);
    $tradeOrder->setTotal($total);

    //In order market we  really change value, in limit juste create order with pending status
    if($tradeOrder->getOrderMethod()->getId() == $this->container->getParameter('order_market')){
      if($tradeOrder->getOrderAction()->getId() == $this->container->getParameter('order_buy')){
        $wallet = $this->incrementeCurrencyWallet($tradeOrder,$em);
        $this->decrementeEuroWallet($tradeOrder, $total);
      }else{
        $wallet = $this->decrementeCurrencyWallet($tradeOrder,$em);
        $this->incrementeEuroWallet($tradeOrder, $total);
      }
      $em->persist($wallet);
    }
    $em->persist($tradeOrder);

    $em->flush();
  }

  /**
  * get good status
  */
  private function getStatus($tradeOrder, $em){
    if($tradeOrder->getOrderMethod()->getId() == $this->container->getParameter('order_market') || $tradeOrder->getOrderStatus() !== NULL){
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
    $priceEur = $tradeOrder->getCurrency()->getPriceEur();
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
