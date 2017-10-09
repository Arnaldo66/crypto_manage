<?php

namespace AppBundle\Tests\Integration;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TradeOrderTest extends WebTestCase
{

    private $client;

    public function setUp(){
      $this->client = static::createClient(array(), array(
          'PHP_AUTH_USER' => 'username',
          'PHP_AUTH_PW'   => 'password',
      ));
    }

    public function testCreateWallet()
    {
        $crawler = $this->client->request('GET', '/u/trade/new');
        $buttonCrawlerNode = $crawler->selectButton('btn-create-trading');

        $form = $buttonCrawlerNode->form(array(
            'trading_wallet[name]'  => 'Name',
        ));
        $this->client->submit($form);
        $this->assertTrue($this->client->getResponse()->isRedirect('/u/trade/wallets'));

        $crawler = $this->client->followRedirect();
        $this->assertCount(1, $crawler->filter('div.alert-success'));
    }

}
