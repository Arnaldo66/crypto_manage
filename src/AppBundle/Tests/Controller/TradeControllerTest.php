<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TradeControllerTest extends WebTestCase
{

    private $client;

    public function setUp(){
      $this->client = static::createClient(array(), array(
          'PHP_AUTH_USER' => 'username',
          'PHP_AUTH_PW'   => 'password',
      ));
    }

    public function testIndexWithoutUser(){
      $client = static::createClient();
      $crawler = $client->request('GET', '/u/trade/wallets');

      $this->assertEquals(302,$client->getResponse()->getStatusCode());
    }

    public function testIndex(){
      $crawler = $this->client->request('GET', '/u/trade/wallets');
      $this->assertEquals(200,$this->client->getResponse()->getStatusCode());
    }

    public function testNewWithoutUser(){
      $client = static::createClient();
      $crawler = $client->request('GET', '/u/trade/new');

      $this->assertEquals(302,$client->getResponse()->getStatusCode());
    }

    public function testNew()
    {
        $crawler = $this->client->request('GET', '/u/trade/new');
        $this->assertEquals(200,$this->client->getResponse()->getStatusCode());
    }

    public function testShowWithoutUser(){
      $client = static::createClient();
      $crawler = $client->request('GET', '/u/trade/wallets/1');

      $this->assertEquals(302,$client->getResponse()->getStatusCode());
    }

    /**
     * Important to respect test order show after new
     */
    public function testShow()
    {
        $crawler = $this->client->request('GET', '/u/trade/wallets/1');
        $this->assertEquals(200,$this->client->getResponse()->getStatusCode());
    }

    public function testShowPublicWallet(){
       $crawler = $this->client->request('GET', '/portefeuilles-publics');
       $this->assertEquals(200,$this->client->getResponse()->getStatusCode());
    }
}
