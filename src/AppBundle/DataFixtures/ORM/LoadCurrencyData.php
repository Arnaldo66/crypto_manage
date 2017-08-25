<?php
namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\Currency;

class LoadCurrencyData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $currency = new Currency();
        $currency->setName('Bitcoin');
        $currency->setSymbol('BTC');

        $manager->persist($currency);
        $manager->flush();
    }
}
