<?php
namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\TradingOrder;

class LoadTradingOrderData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $tradeOrder = new TradingOrder();
        $tradeOrder->setPrice(3500);
        $tradeOrder->setAmount(2);
        $tradeOrder->setTotal(7000);
        $tradeOrder->setCurrency(
          $this->getReference('currency_not_reel')
        );

        $tradeOrder->setTradingWallet(
          $this->getReference('trading_wallet_unit_test')
        );

      //  $tradeOrder->setOrderMethod();
      //  $tradeOrder->setOrderAction();
      //  $tradeOrder->setOrderStatus();

        /*$manager->persist($tradeOrder);
        $manager->flush();*/


    }

    public function getOrder()
    {
        return 9;
    }
}
