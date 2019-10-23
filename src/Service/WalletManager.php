<?php
namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;

use App\Entity\CurrencyWallet;
use App\Entity\TradingWallet;
use App\Entity\TradingOrder;

class WalletManager
{
    private $order_buy;
    private $order_status_pending;
    private $order_sell;
    private $order_market;
    private $order_limit;
    private $order_status_ok;
    private $em;

    public function __construct(
      EntityManagerInterface $em,
      $order_buy,
      $order_status_pending,
      $order_sell,
      $order_market,
      $order_status_ok,
      $order_limit
    ) {
        $this->em = $em;
        $this->order_buy = $order_buy;
        $this->order_status_pending = $order_status_pending;
        $this->order_sell = $order_sell;
        $this->order_market = $order_market;
        $this->order_status_ok = $order_status_ok;
        $this->order_limit = $order_limit;
    }

    /**
     * récupère le total de toutes les currency wallet
     **/
    public function getTotalCurrencyWalletValue(TradingWallet $tradingWallet)
    {
        $wallets = $tradingWallet->getCurrencyWallets();
        $total = 0;

        if ($wallets->count() === 0) {
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
    public function canFinaliseOrder(TradingOrder $tradeOrder)
    {
        if ($tradeOrder->getOrderAction()->getId() == $this->order_buy) {
            // achat: total >= montant euro wallet && order pending ?
            $totalOrderPending = $this->getTotalWalletPending($tradeOrder->getTradingWallet(), $this->em);
            $totalWallet = $tradeOrder->getTradingWallet()->getEuroWallet()->getAmount() - $totalOrderPending;
            if ($totalWallet < $tradeOrder->getTotal()) {
                return array("success"=>false, "message"=>"Vous n'avez pas les fonds nécessaires");
            }
        } else {
            // vente: exists wallet && amount >= trader order amount
            $wallet = $this->em->getRepository("App:CurrencyWallet")->findOneBy(array(
                'tradingWallet' => $tradeOrder->getTradingWallet(),
                'currency' => $tradeOrder->getCurrency()
            ));
            $amoutWallet = $wallet->getAmount() - $this->getAmountCurrencyWalletPending($tradeOrder, $this->em);
            if ($wallet === null || $amoutWallet < $tradeOrder->getAmount()) {
                return array("success"=>false, "message"=>"Vous n'avez pas les fonds nécessaires");
            }
        }
        return array("success"=>true, "message"=>"OK");
    }

    /**
    * get total amoutn all order pending for this wallet
    **/
    private function getTotalWalletPending(TradingWallet $tradingWallet)
    {
        $total = 0;
        $orders = $this->em->getRepository('App:TradingOrder')->findBy(array(
          'tradingWallet' => $tradingWallet,
          'orderStatus' => $this->order_status_pending,
          'orderAction' => $this->order_buy
        ));
        foreach ($orders as $order) {
            $total += $order->getTotal();
        }

        return $total;
    }

    /**
    *
    */
    private function getAmountCurrencyWalletPending(TradingOrder $tradeOrder)
    {
        $amount = 0;
        $orders = $this->em->getRepository('App:TradingOrder')->findBy(array(
          'tradingWallet' => $tradeOrder->getTradingWallet(),
          'orderStatus' => $this->order_status_pending,
          'orderAction' => $this->order_sell,
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
    public function finaliseOrder(TradingOrder $tradeOrder, $force_order = 0)
    {
        $tradeOrder->setOrderStatus($this->getStatus($tradeOrder));
        $total = $this->calculateTotal($tradeOrder);
        $tradeOrder->setTotal($total);

        //In order market we  really change value, in limit juste create order with pending status
        if ($tradeOrder->getOrderMethod()->getId() == $this->order_market || $force_order == 1) {
            if ($tradeOrder->getOrderAction()->getId() == $this->order_buy) {
                $wallet = $this->incrementeCurrencyWallet($tradeOrder);
                $this->decrementeEuroWallet($tradeOrder, $total);
            } else {
                $wallet = $this->decrementeCurrencyWallet($tradeOrder);
                $this->incrementeEuroWallet($tradeOrder, $total);
            }
            $this->em->persist($wallet);
        }
        $this->em->persist($tradeOrder);

        $this->em->flush();
    }

    /**
    * get good status
    */
    private function getStatus(TradingOrder $tradeOrder)
    {
        if ($tradeOrder->getOrderMethod()->getId() == $this->order_market || $tradeOrder->getOrderStatus() !== null) {
            $status = $this->order_status_ok;
        } else {
            $status = $this->order_status_pending;
        }
        return $this->em->getRepository("App:OrderStatus")->find($status);
    }

    /**
    * calculate total
    */
    private function calculateTotal(TradingOrder $tradeOrder)
    {
        if ($tradeOrder->getOrderMethod()->getId() == $this->order_market) {
            $priceEur = $tradeOrder->getCurrency()->getPriceEur();
            $priceEur = $priceEur * $tradeOrder->getAmount();
        } else {
            $priceEur = $tradeOrder->getTotal();
        }
        return $priceEur;
    }

    /**
    * incrémente wallet
    */
    private function incrementeCurrencyWallet(TradingOrder $tradeOrder)
    {
        //check if exists ? create : update
        $wallet = $this->em->getRepository("App:CurrencyWallet")->findOneBy(array(
          'tradingWallet' => $tradeOrder->getTradingWallet(),
          'currency' => $tradeOrder->getCurrency()
        ));
        if ($wallet === null) {
            $wallet = new CurrencyWallet();
            $wallet->setCurrency($tradeOrder->getCurrency());
            $wallet->setTradingWallet($tradeOrder->getTradingWallet());
            $wallet->setAmount($tradeOrder->getAmount());
        } else {
            $wallet->setAmount($tradeOrder->getAmount() + $wallet->getAmount());
        }

        return $wallet;
    }

    /**
    * decremente euro wallet
    */
    private function decrementeEuroWallet(TradingOrder $tradeOrder, $total)
    {
        $euroWallet = $tradeOrder->getTradingWallet()->getEuroWallet();
        $euroWallet->setAmount($euroWallet->getAmount() - $total);
    }

    /**
    * decremente wallet
    */
    private function decrementeCurrencyWallet(TradingOrder $tradeOrder)
    {
        $wallet = $this->em->getRepository("App:CurrencyWallet")->findOneBy(array(
          'tradingWallet' => $tradeOrder->getTradingWallet(),
          'currency' => $tradeOrder->getCurrency()
        ));
        $wallet->setAmount($wallet->getAmount() - $tradeOrder->getAmount());

        return $wallet;
    }

    /**
    * incremente euro wallet
    */
    private function incrementeEuroWallet(TradingOrder $tradeOrder, $total)
    {
        $euroWallet = $tradeOrder->getTradingWallet()->getEuroWallet();
        $euroWallet->setAmount($euroWallet->getAmount() + $total);
    }
}
