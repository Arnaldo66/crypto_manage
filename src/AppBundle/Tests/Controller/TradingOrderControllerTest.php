<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TradingOrderControllerTest extends WebTestCase
{

    private $client;

    public function setUp(){
      $this->client = static::createClient(array(), array(
          'PHP_AUTH_USER' => 'username',
          'PHP_AUTH_PW'   => 'password',
      ));
    }

    public function testNew()
    {
        $client = static::createClient();

        $crawler = $this->client->request('GET', '/u/trade/order/new');
        $this->assertEquals(200,$this->client->getResponse()->getStatusCode());
    }

}
