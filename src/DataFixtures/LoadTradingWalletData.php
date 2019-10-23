<?php

namespace App\DataFixtures;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\TradingWallet;
use App\Entity\EuroWallet;
use App\Entity\CurrencyWallet;

class LoadTradingWalletData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $tradingWallet = new TradingWallet();
        $euroWallet = new EuroWallet();

        $tradingWallet->setUser($this->getReference('user'));
        $tradingWallet->setName('Name');
        $tradingWallet->setPublic(true);
        $tradingWallet->setInitialAmount(15000);

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
        $tradingWallet->setInitialAmount(15000);

        $euroWallet->setAmount(0);
        $euroWallet->setTradingWallet($tradingWallet);
        $tradingWallet->setEuroWallet($euroWallet);

        $manager->persist($euroWallet);
        $manager->persist($tradingWallet);
        $manager->flush();

        //create on only for unit test Service
        $tradingWallet = new TradingWallet();
        $euroWallet = new EuroWallet();
        $currencyWallet = new CurrencyWallet();

        $tradingWallet->setUser($this->getReference('user'));
        $tradingWallet->setName('service test');
        $tradingWallet->setPublic(true);
        $tradingWallet->setInitialAmount(15000);

        $euroWallet->setAmount(15000);
        $euroWallet->setTradingWallet($tradingWallet);
        $tradingWallet->setEuroWallet($euroWallet);

        $currencyWallet->setAmount(2);
        $currencyWallet->setCurrency($this->getReference('currency_not_reel'));
        $currencyWallet->setTradingWallet($tradingWallet);
        $tradingWallet->addCurrencyWallet($currencyWallet);

        $manager->persist($currencyWallet);
        $manager->persist($euroWallet);
        $manager->persist($tradingWallet);
        $manager->flush();

        $this->addReference('trading_wallet_unit_test', $tradingWallet);

    }

    public function getOrder()
    {
        return 3;
    }
}
