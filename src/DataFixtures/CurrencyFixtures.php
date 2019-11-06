<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Currency;

class CurrencyFixtures extends Fixture
{
    public const CURRENCY_REFERENCE = 'currency';

    public function load(ObjectManager $manager)
    {
        $currency = new Currency();

        $currency->setName('bitcoin');
        $currency->setSymbol('BTC');
        $currency->setUniqueName('bitcoin');
        $currency->setPriceEur(1000);
        $currency->setPriceUsd(1200);
        $currency->setPriceBtc(1);
        $currency->setRank(1);

        $manager->persist($currency);
        $manager->flush();

        $this->addReference(self::CURRENCY_REFERENCE, $currency);
    }
}
