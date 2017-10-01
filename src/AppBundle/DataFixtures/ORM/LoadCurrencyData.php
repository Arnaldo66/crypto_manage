<?php
namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\Currency;

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
    }

    public function getOrder()
    {
        return 1;
    }
}
