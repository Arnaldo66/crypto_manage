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

    public function testCreateWallet()
    {
        $crawler = $this->client->request('GET', '/user/trade/new');
        $buttonCrawlerNode = $crawler->selectButton('btn-create-trading');

        $form = $buttonCrawlerNode->form(array(
            'trading_wallet[name]'  => 'Name',
        ));
        $this->client->submit($form);
        $this->assertTrue($this->client->getResponse()->isRedirect('/user/trade/wallets'));

        $crawler = $this->client->followRedirect();
        $this->assertCount(1, $crawler->filter('div.alert-success'));
    }

}
