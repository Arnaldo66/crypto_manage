<?php
namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\MyCryptoWallet;

class LoadMyCryptoWalletData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $myCryptoWallet = new MyCryptoWallet;

        $myCryptoWallet->setUser($this->getReference('user'));
        $myCryptoWallet->setName('Name');

        $manager->persist($myCryptoWallet);
        $manager->flush();

    }

    public function getOrder()
    {
        return 10;
    }
}