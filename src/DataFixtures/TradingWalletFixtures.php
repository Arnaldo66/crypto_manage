<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use App\Entity\TradingWallet;
use App\Entity\EuroWallet;

class TradingWalletFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $tradingWallet = new TradingWallet();

        $tradingWallet->setInitialAmount(1000);
        $tradingWallet->setName('test');
        $tradingWallet->setPublic(true);
        $tradingWallet->setUser($this->getReference(UserFixtures::USER_REFERENCE));


        $euroWallet = new EuroWallet();
        $euroWallet->setAmount(1000);

        $euroWallet->setTradingWallet($tradingWallet);
        $tradingWallet->setEuroWallet($euroWallet);

        $manager->persist($tradingWallet);
        $manager->persist($euroWallet);
        $manager->flush();


    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
        ];
    }
}
