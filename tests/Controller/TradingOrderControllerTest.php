<?php

namespace App\Tests\Controller;

use App\Entity\Currency;
use App\Entity\TradingOrder;
use App\Entity\TradingWallet;
use Symfony\Component\Panther\PantherTestCase;

class TradingOrderControllerTest extends PantherTestCase
{
    public function testWithoutClient()
    {
        $client = static::createClient();
        $client->request('GET', '/u/trade/order/new');

        $this->assertResponseRedirects('http://localhost/login', 302);
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
        $manager = $client->getContainer()->get('doctrine');
        $currency = $manager->getRepository(Currency::class)->findOneBySymbol('BTC');
        $tradingWallet = $manager->getRepository(TradingWallet::class)->findOneByName('test');

        $session = $client->getContainer()->get('session');
        $session->set('current_wallet_id', $tradingWallet->getId());

        $crawler = $client->request('GET', '/u/trade/order/new');
        $this->assertResponseIsSuccessful();


        $form = $crawler->selectButton('btn-create-order')->form();
        $form['trading_order_first_step[currency]'] = $currency->getId();

        $crawler = $client->submit($form);
        $this->assertResponseIsSuccessful();

        $form = $crawler->selectButton('btn-finalise-order')->form();

        $form['trading_order_next_step[total]'] = 100;
        $form['trading_order_next_step[amount]'] = 1;

        $client->submit($form);

        $this->assertResponseRedirects('/u/trade/wallets/' . $tradingWallet->getId(), 302);
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
        $currency = $manager->getRepository(Currency::class)->findOneBySymbol('BTC');
        $tradingOrder = $manager->getRepository(TradingOrder::class)->findOneByCurrency($currency);

        $client->request('DELETE', '/u/trade/order/cancel/' . $tradingOrder->getId());
        $this->assertResponseRedirects('/u/trade/wallets/' . $tradingOrder->getTradingWallet()->getId(), 302);
    }
}
