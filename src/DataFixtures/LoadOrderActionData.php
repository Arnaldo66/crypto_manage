<?php

namespace App\DataFixtures;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\OrderAction;

class LoadOrderActionData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $action = new OrderAction();
        $action->setName('Achat');
        $manager->persist($action);
        $manager->flush();

        $action = new OrderAction();
        $action->setName('Vente');
        $manager->persist($action);
        $manager->flush();

    }

    public function getOrder()
    {
        return 6;
    }
}
