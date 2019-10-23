<?php

namespace App\DataFixtures;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Currency;

class LoadCurrencyData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $currency = new Currency();
        $currency->setName('Bitcoin');
        $currency->setSymbol('BTC');
        $currency->setUniqueName('bitcoin');
        $currency->setPriceUsd('4000');
        $currency->setPriceEur('3500');
        $currency->setRank(1);

        $manager->persist($currency);
        $manager->flush();

        $this->addReference('currency', $currency);


        $currency = new Currency();
        $currency->setName('nexisterajamaislagny');
        $currency->setSymbol('JXRTKK');
        $currency->setUniqueName('nexisterajamaislagny');
        $currency->setPriceUsd('4000');
        $currency->setPriceEur('3500');
        $currency->setRank(2000);

        $manager->persist($currency);
        $manager->flush();

        $this->addReference('currency_not_reel', $currency);
    }

    public function getOrder()
    {
        return 1;
    }
}
