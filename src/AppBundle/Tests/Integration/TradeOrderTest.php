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
        $this->client->submit($form);
        $this->assertEquals(200,$this->client->getResponse()->getStatusCode());
    }

}
