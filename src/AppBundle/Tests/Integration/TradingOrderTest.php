<?php

namespace AppBundle\Tests\Integration;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockFileSessionStorage;

class TradingOrderTest extends WebTestCase
{

    private $client;
    const GOOD_WALLET = '1';
    const MARKET_METHOD = '1';
    const LIMIT_METHOD = '1';
    const NO_MONNEY_WALLET = '2';

    public function setUp(){
      $this->client = static::createClient(array(), array(
          'PHP_AUTH_USER' => 'username',
          'PHP_AUTH_PW'   => 'password',
      ));
    }

    private function goodValue($method, $wallet){
      return array(
          'trading_order_next_step[orderAction]'  => '1',
          'trading_order_next_step[orderMethod]'  => $method,
          'trading_order_next_step[tradingWallet]' => $wallet,
          'trading_order_next_step[amount]' => '0.00001',
          'trading_order_next_step[total]' => '15',
          'trading_order_next_step[price]' => '15',
      );
    }

    private function emptyValue(){
      return array(
          'trading_order_next_step[orderAction]'  => '1',
          'trading_order_next_step[orderMethod]'  => '1',
          'trading_order_next_step[tradingWallet]' => '1',
          'trading_order_next_step[amount]' => '',
          'trading_order_next_step[total]' => '',
          'trading_order_next_step[price]' => '',
      );
    }

    private function setSessionWallet($walletId){
      $container = $this->client->getContainer();
      $session = $container->get('session');
      $session->set('current_wallet_id', $walletId);
      $session->save();
    }

    public function firstStep(){
       $crawler = $this->client->request('GET', '/u/trade/order/new');
       $buttonNode = $crawler->selectButton('btn-create-order');
       $form = $buttonNode->form(array(
           'trading_order_first_step[currency]'  => '1',
       ));
       $crawler = $this->client->submit($form);
       $this->assertEquals(200,$this->client->getResponse()->getStatusCode());
       return $crawler;
    }

    public function testCreateOrderMarket(){
        $this->setSessionWallet(self::GOOD_WALLET);
        $crawler = $this->firstStep();

        $buttonCrawlerNode = $crawler->selectButton('btn-finalise-order');
        $form = $buttonCrawlerNode->form($this->goodValue(self::MARKET_METHOD, self::GOOD_WALLET));
        $crawler = $this->client->submit($form);
        $this->assertTrue($this->client->getResponse()->isRedirect('/u/trade/wallets/'.self::GOOD_WALLET));
        $crawler = $this->client->followRedirect();
        $this->assertCount(1, $crawler->filter('div.alert-success'));
    }

    public function testCreateOrderLimit(){
        $this->setSessionWallet(self::GOOD_WALLET);
        $crawler = $this->firstStep();

        $buttonCrawlerNode = $crawler->selectButton('btn-finalise-order');
        $form = $buttonCrawlerNode->form($this->goodValue(self::LIMIT_METHOD , self::GOOD_WALLET));
        $crawler = $this->client->submit($form);
        $this->assertTrue($this->client->getResponse()->isRedirect('/u/trade/wallets/'.self::GOOD_WALLET));
        $crawler = $this->client->followRedirect();
        $this->assertCount(1, $crawler->filter('div.alert-success'));
    }


    public function testCreateOrderWithNoMonney(){
      $this->setSessionWallet(self::NO_MONNEY_WALLET);
      $crawler = $this->firstStep();

      $buttonCrawlerNode = $crawler->selectButton('btn-finalise-order');
      $form = $buttonCrawlerNode->form($this->goodValue(self::MARKET_METHOD, self::NO_MONNEY_WALLET));
      $crawler = $this->client->submit($form);
      $this->assertCount(1, $crawler->filter('div.alert-danger'));
    }

    public function testCreateWithoutValue(){
        $this->setSessionWallet(self::GOOD_WALLET);
        $crawler = $this->firstStep();

        $buttonCrawlerNode = $crawler->selectButton('btn-finalise-order');
        $form = $buttonCrawlerNode->form($this->emptyValue());
        $crawler = $this->client->submit($form);
        $this->assertCount(3, $crawler->filter('span.error'));
    }
}
