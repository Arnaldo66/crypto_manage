<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\OrderStatus;

class OrderStatusFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $orderStatus = new OrderStatus();

        $orderStatus->setName('Validé');

        $manager->persist($orderStatus);
        $manager->flush();

        $orderStatus = new OrderStatus();

        $orderStatus->setName('En cours');

        $manager->persist($orderStatus);
        $manager->flush();

        $orderStatus = new OrderStatus();

        $orderStatus->setName('Annulé');

        $manager->persist($orderStatus);
        $manager->flush();

    }
}
