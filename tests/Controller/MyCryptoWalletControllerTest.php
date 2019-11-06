<?php

namespace App\Tests\Controller;

use Symfony\Component\Panther\PantherTestCase;
use App\Entity\MyCryptoWallet;

class MyCryptoWalletControllerTest extends PantherTestCase
{
    public function testWithoutUser()
    {
        $client = static::createClient();
        $client->request('GET', '/u/my-crypto/dashboard');

        $this->assertResponseRedirects('http://localhost/login', 302);
    }

    public function testMyCryptoWallet()
    {
        $client = static::createClient(
            [],
            [
                'PHP_AUTH_USER' => 'user',
                'PHP_AUTH_PW' => 'phpunit_user'
            ]
        );
        $client->request('GET', '/u/my-crypto/dashboard');
        $this->assertResponseIsSuccessful();
    }

    public function testNewCryptoWallet()
    {
        $client = static::createClient(
            [],
            [
                'PHP_AUTH_USER' => 'user',
                'PHP_AUTH_PW' => 'phpunit_user'
            ]
        );
        $crawler = $client->request('GET', '/u/my-crypto/new');
        $this->assertResponseIsSuccessful();

        $form = $crawler->selectButton('btn-create-contact')->form();
        $form['my_crypto_wallet[name]'] = 'test';

        $client->submit($form);
        $this->assertResponseRedirects('/u/my-crypto/dashboard', 302);
    }

    public function testShow()
    {
        $client = static::createClient(
            [],
            [
                'PHP_AUTH_USER' => 'user',
                'PHP_AUTH_PW' => 'phpunit_user'
            ]
        );

        $manager = $client->getContainer()->get('doctrine');
        $myCryptoWallet = $manager->getRepository(MyCryptoWallet::class)->findOneByName('test');

        $client->request('GET', '/u/my-crypto/' . $myCryptoWallet->getId());
        $this->assertResponseIsSuccessful();
    }
}
