<?php

namespace AppBundle\Tests\Service;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

use AppBundle\Service\WalletManager;
use AppBundle\Entity\TradingWallet;

class WalletManagerTest extends KernelTestCase
{

    private $order_buy;
    private $order_status_pending;
    private $order_sell;
    private $order_market;
    private $order_limit;
    private $order_status_ok;
    private $em;
    private $walletManager;
    private $tradingWallet;

    const CURRENCY_WALLET_TOTAL = 7000;
    const NO_ORDER_PENDING = 0;

    public function setUp(){
      self::bootKernel();
      $container = static::$kernel->getContainer();
      $this->em = $container->get('doctrine')->getManager();
      $this->order_buy = $container->getParameter('order_buy');
      $this->order_status_pending = $container->getParameter('order_status_pending');
      $this->order_sell = $container->getParameter('order_sell');
      $this->order_market = $container->getParameter('order_market');
      $this->order_status_ok = $container->getParameter('order_status_ok');
      $this->order_limit = $container->getParameter('order_limit');
      $this->walletManager = new WalletManager(
        $this->em, $this->order_buy, $this->order_status_pending,
        $this->order_sell, $this->order_market, $this->order_status_ok,
        $this->order_limit
      );
      $this->tradingWallet = $this->getTradingWallet();
    }

    public function getTradingWallet(){
      return $this->em->getRepository('AppBundle:TradingWallet')->findOneByName(
        'service test'
      );
    }

    /**
     * Specific wallet for test 2(amount) * 3500(priceEur) = 7000
     */
    public function testTotalCurrencyWallet(){
        $result = $this->walletManager->getTotalCurrencyWalletValue(
          $this->tradingWallet
        );
        $this->assertEquals($result, self::CURRENCY_WALLET_TOTAL);
    }

    /**
     * test unit totalWalletPending
     */
    public function testTotalWalletPending(){
        $reflection = new \ReflectionClass(get_class($this->walletManager));
        $method = $reflection->getMethod('getTotalWalletPending');
        $method->setAccessible(true);
        $result = $method->invokeArgs($this->walletManager, array($this->tradingWallet));
        $this->assertEquals($result, self::NO_ORDER_PENDING);
    }

    //Creer un ordre achat + ordre de vente
    
}
