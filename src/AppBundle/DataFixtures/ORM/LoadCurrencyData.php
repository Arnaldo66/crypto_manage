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

        $manager->persist($currency);
        $manager->flush();

        $this->addReference('currency', $currency);
    }

    public function getOrder()
    {
        return 1;
    }
}
