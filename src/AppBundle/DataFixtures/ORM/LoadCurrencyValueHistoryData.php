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
        $currencyValueHistory->setOpen(1.00);
        $currencyValueHistory->setClose(1.00);
        $currencyValueHistory->setLow(1.00);
        $currencyValueHistory->setHight(1.00);
        $currencyValueHistory->setHight(1.00);
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
