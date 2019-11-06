<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\OrderAction;

class OrderActionFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $orderAction = new OrderAction();

        $orderAction->setName('Achat');

        $manager->persist($orderAction);
        $manager->flush();

    }
}
