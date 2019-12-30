<?php

namespace App\Tests\Controller;

use App\Entity\Currency;
use App\Entity\MyCryptoWallet;
use App\Entity\MyCryptoWalletDetail;
use Symfony\Component\Panther\PantherTestCase;

class MyCryptoWalletDetailControllerTest extends PantherTestCase
{
    public function testNewDetail()
    {
        $client = static::createClient(
            [],
            [
                'PHP_AUTH_USER' => 'user',
                'PHP_AUTH_PW' => 'phpunit_user'
            ]
        );
        $manager = $client->getContainer()->get('doctrine');
        $myCryptoWallet = $manager->getRepository(MyCryptoWallet::class)->findOneByName('wallet');

        $crawler = $client->request('GET', '/u/my-crypto-detail/' . $myCryptoWallet->getId() . '/new');
        $this->assertResponseIsSuccessful();

        $currency = $manager->getRepository(Currency::class)->findOneBySymbol('BTC');

        $form = $crawler->selectButton('btn-add-crypto')->form();
        $form['my_crypto_wallet_detail[amount]'] = 1230;
        $form['my_crypto_wallet_detail[currency]'] = $currency->getId();

        $client->submit($form);
        $this->assertResponseRedirects('/u/my-crypto/' . $myCryptoWallet->getId() , 302);
    }

//    public function testDelete()
//    {
//        $client = static::createClient(
//            [],
//            [
//                'PHP_AUTH_USER' => 'user',
//                'PHP_AUTH_PW' => 'phpunit_user'
//            ]
//        );
//        $manager = $client->getContainer()->get('doctrine');
//        $myCryptoWallet = $manager->getRepository(MyCryptoWallet::class)->findOneByName('wallet');
//        $myCryptoWalletDetail = $manager->getRepository(MyCryptoWalletDetail::class)->findOneByAmount(1230);
//
//        $client->request('DELETE', '/u/my-crypto-detail/delete/' . $myCryptoWalletDetail->getId());
//        $this->assertResponseRedirects('/u/my-crypto/' . $myCryptoWallet->getId() , 302);
//    }

    public function testPost()
    {
        $client = static::createClient(
            [],
            [
                'PHP_AUTH_USER' => 'user',
                'PHP_AUTH_PW' => 'phpunit_user'
            ]
        );
        $manager = $client->getContainer()->get('doctrine');
        $myCryptoWallet = $manager->getRepository(MyCryptoWallet::class)->findOneByName('wallet');
        $myCryptoWalletDetail = $manager->getRepository(MyCryptoWalletDetail::class)->findOneByAmount(1230);

        $client->request('POST', '/u/my-crypto-detail/delete/' . $myCryptoWalletDetail->getId());
        $this->assertResponseRedirects('/u/my-crypto/' . $myCryptoWallet->getId() , 302);
    }
}
