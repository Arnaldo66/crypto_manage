<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class MyCryptoWalletDetailControllerTest extends WebTestCase
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
        $crawler = $this->client->request('GET', '/u/my-crypto-detail/new');
        $this->assertEquals(200,$this->client->getResponse()->getStatusCode());
    }

}
