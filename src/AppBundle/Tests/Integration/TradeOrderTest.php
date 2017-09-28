<?php

namespace AppBundle\Tests\Integration;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TradeOrderTest extends WebTestCase
{

    private $client;

    public function setUp(){
      exec('php bin/console doctrine:fixtures:load -e test --no-interaction --purge-with-truncate');
      $this->client = static::createClient(array(), array(
          'PHP_AUTH_USER' => 'username',
          'PHP_AUTH_PW'   => 'password',
      ));
    }

    public function testCreateOrder(){
        $crawler = $this->client->request('GET', '/user/trade/order/new');
        $this->assertEquals(200,$this->client->getResponse()->getStatusCode());

        $buttonCrawlerNode = $crawler->selectButton('btn-create-order');
        $form = $buttonCrawlerNode->form(array(
            'trading_order_first_step[currency]'  => '1',
        ));
        $crawler = $this->client->submit($form);
        $this->assertEquals(200,$this->client->getResponse()->getStatusCode());

        $buttonCrawlerNode = $crawler->selectButton('btn-finalise-order');
        $form = $buttonCrawlerNode->form(array(
            'trading_order_next_step[orderAction]'  => '1',
            'trading_order_next_step[orderMethod]'  => '1',
            'trading_order_next_step[tradingWallet]' => '1',
            'trading_order_next_step[amount]' => '1',
            'trading_order_next_step[total]' => '3000',
            'trading_order_next_step[price]' => '3000',
        ));
        $crawler = $this->client->submit($form);
        $this->assertTrue($this->client->getResponse()->isRedirect('/user/trade/wallets/1'));
        $crawler = $this->client->followRedirect();
        $this->assertCount(1, $crawler->filter('div.alert-success'));
    }

}
