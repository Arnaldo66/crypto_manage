<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\DataFixtures\CurrencyFixtures;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use App\Entity\CurrencyValueMoment;

class CurrencyValueMomentFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $currency = new CurrencyValueMoment();

        $currency->setRank(1);
        $currency->setPriceUsd(1200);
        $currency->setPriceEur(1000);
        $currency->setPriceBtc(1);
        $currency->setMarketCapUsd(10000);
        $currency->setMarketCapEur(10000);
        $currency->setAvailableSupply(17000);
        $currency->setTotalSupply(21000000);
        $currency->setPercentChange1h(1);
        $currency->setPercentChange7d(1);
        $currency->setPercentChange24h(1);
        $currency->setCurrency($this->getReference(CurrencyFixtures::CURRENCY_REFERENCE));

        $manager->persist($currency);
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            CurrencyFixtures::class,
        ];
    }
}
