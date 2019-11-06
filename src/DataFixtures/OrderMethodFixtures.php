<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\OrderMethod;

class OrderMethodFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $orderMethod = new OrderMethod();

        $orderMethod->setName('Market');

        $manager->persist($orderMethod);
        $manager->flush();

    }
}
