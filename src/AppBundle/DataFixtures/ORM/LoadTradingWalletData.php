<?php
namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\TradingWallet;
use AppBundle\Entity\EuroWallet;

class LoadTradingWalletData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $tradingWallet = new TradingWallet();
        $euroWallet = new EuroWallet();

        $tradingWallet->setUser($this->getReference('user'));
        $tradingWallet->setName('Name');
        $tradingWallet->setPublic(true);

        $euroWallet->setAmount(15000);

        $euroWallet->setTradingWallet($tradingWallet);
        $tradingWallet->setEuroWallet($euroWallet);

        $manager->persist($euroWallet);
        $manager->persist($tradingWallet);
        $manager->flush();

        //One more but with no monney
        $tradingWallet = new TradingWallet();
        $euroWallet = new EuroWallet();

        $tradingWallet->setUser($this->getReference('user'));
        $tradingWallet->setName('NoMonney');
        $tradingWallet->setPublic(true);

        $euroWallet->setAmount(0);

        $euroWallet->setTradingWallet($tradingWallet);
        $tradingWallet->setEuroWallet($euroWallet);

        $manager->persist($euroWallet);
        $manager->persist($tradingWallet);
        $manager->flush();

    }

    public function getOrder()
    {
        return 3;
    }
}
