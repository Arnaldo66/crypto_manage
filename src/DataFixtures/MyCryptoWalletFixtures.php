<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\MyCryptoWallet;

class MyCryptoWalletFixtures extends Fixture
{

    public function load(ObjectManager $manager)
    {
        $cryptoWallet = new MyCryptoWallet();

        $cryptoWallet->setName('wallet');
        $cryptoWallet->setUser($this->getReference(UserFixtures::USER_REFERENCE));

        $manager->persist($cryptoWallet);
        $manager->flush();

    }
}
