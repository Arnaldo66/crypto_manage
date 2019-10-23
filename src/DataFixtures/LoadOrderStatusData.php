<?php

namespace App\DataFixtures;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\OrderStatus;

class LoadOrderStatusData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $status = new OrderStatus();
        $status->setName('Finalisé');
        $manager->persist($status);
        $manager->flush();

        $status = new OrderStatus();
        $status->setName('En cours');
        $manager->persist($status);
        $manager->flush();

        $status = new OrderStatus();
        $status->setName('Annulé');
        $manager->persist($status);
        $manager->flush();

    }

    public function getOrder()
    {
        return 4;
    }
}
