<?php
namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\CurrencyValueHistory;

class LoadCurrencyValueHistoryData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $currencyValueHistory = new CurrencyValueHistory;
        $currencyValueHistory->setLowUsd(1.00);
        $currencyValueHistory->setLowEur(1.00);
        $currencyValueHistory->setHightUsd(1.00);
        $currencyValueHistory->setHightEur(1.00);
        $currencyValueHistory->setAverage(1.00);
        $currencyValueHistory->setCurrency($this->getReference('currency'));
        $currencyValueHistory->setDay(new \DateTime());

        $manager->persist($currencyValueHistory);
        $manager->flush();
    }

    public function getOrder()
    {
        return 2;
    }
}
