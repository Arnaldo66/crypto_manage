<?php

namespace App\Tests\Controller;

use App\Entity\TradingWallet;
use Symfony\Component\Panther\PantherTestCase;

class TradeControllerTest extends PantherTestCase
{
    public function testWithoutUser()
    {
        $client = static::createClient();
        $client->request('GET', '/u/trade/wallets');

        $this->assertResponseRedirects('http://localhost/login', 302);
    }

    public function testWallet()
    {
        $client = static::createClient(
            [],
            [
                'PHP_AUTH_USER' => 'user',
                'PHP_AUTH_PW' => 'phpunit_user'
            ]
        );
        $client->request('GET', '/u/trade/wallets');

        $this->assertResponseIsSuccessful();
    }

    public function testPublicWallet()
    {
        $client = static::createClient();
        $client->request('GET', '/portefeuilles-publics');
        $this->assertResponseIsSuccessful();
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
        $tradingWallet = $manager->getRepository(TradingWallet::class)->findOneByName('test');
        $client->request('GET', '/u/trade/wallets/' . $tradingWallet->getId());
        $this->assertResponseIsSuccessful();
    }

    public function testHistory()
    {
        $client = static::createClient(
            [],
            [
                'PHP_AUTH_USER' => 'user',
                'PHP_AUTH_PW' => 'phpunit_user'
            ]
        );
        $manager = $client->getContainer()->get('doctrine');
        $tradingWallet = $manager->getRepository(TradingWallet::class)->findOneByName('test');
        $client->request('GET', '/u/trade/wallets/' . $tradingWallet->getId() . '/history');
        $this->assertResponseIsSuccessful();
    }

    public function testNew()
    {
        $client = static::createClient(
            [],
            [
                'PHP_AUTH_USER' => 'user',
                'PHP_AUTH_PW' => 'phpunit_user'
            ]
        );
        $crawler = $client->request('GET', '/u/trade/new');
        $this->assertResponseIsSuccessful();

        $form = $crawler->selectButton('btn-create-trading')->form();
        $form['trading_wallet[name]'] = 'test-delete';
        $form['trading_wallet[initialAmount]'] = 1000;
        $form['trading_wallet[public]'] = 1;

        $client->submit($form);
        $this->assertResponseRedirects('/u/trade/wallets', 302);
    }

    public function testDelete()
    {
        $client = static::createClient(
            [],
            [
                'PHP_AUTH_USER' => 'user',
                'PHP_AUTH_PW' => 'phpunit_user'
            ]
        );
        $manager = $client->getContainer()->get('doctrine');
        $tradingWallet = $manager->getRepository(TradingWallet::class)->findOneByName('test-delete');

        $client->request('DELETE', '/u/trade/delete/' . $tradingWallet->getId());
        $this->assertResponseRedirects('/u/trade/wallets', 302);
    }
}
