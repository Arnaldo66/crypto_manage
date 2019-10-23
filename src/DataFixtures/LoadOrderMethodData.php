<?php

namespace App\DataFixtures;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\OrderMethod;

class LoadOrderMethodData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $method = new OrderMethod();
        $method->setName('Market');
        $manager->persist($method);
        $manager->flush();

        $method = new OrderMethod();
        $method->setName('Limit');
        $manager->persist($method);
        $manager->flush();

    }

    public function getOrder()
    {
        return 5;
    }
}
