<?php
namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

use AppBundle\Entity\CurrencyValueMoment;

class LoadCurrencyValueMomentData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $currencyValueMoment = new CurrencyValueMoment();

        $currencyValueMoment->setCurrency($this->getReference('currency'));
        $currencyValueMoment->setRank(1);
        $currencyValueMoment->setPriceUsd(3000);
        $currencyValueMoment->setPriceBtc(1);
        $currencyValueMoment->setAvailableSupply(1);
        $currencyValueMoment->setMarketCapUsd(1);
        $currencyValueMoment->setTotalSupply(1);
        $currencyValueMoment->setPercentChange1h(1);
        $currencyValueMoment->setPercentChange24h(1);
        $currencyValueMoment->setPercentChange7d(1);

        $currencyValueMoment->setPriceEur(2500);
        $currencyValueMoment->setMarketCapEur(1);
        $currencyValueMoment->setVolumeUsd24h(1);
        $currencyValueMoment->setVolumeEur24h(1);
        $currencyValueMoment->setLastUpdated(time());

        $manager->persist($currencyValueMoment);
        $manager->flush();

    }

    public function getOrder()
    {
        return 7;
    }
}
