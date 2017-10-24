<?php

namespace AppBundle\Tests\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class WalletControllerTest extends WebTestCase
{

    private $client;

    public function setUp(){
      $this->client = static::createClient(array(), array(
          'PHP_AUTH_USER' => 'superadmin',
          'PHP_AUTH_PW'   => 'password',
      ));
    }

    public function testWallets()
    {
        $crawler = $this->client->request('GET', '/egbo/wallets');
        $this->assertEquals(200,$this->client->getResponse()->getStatusCode());
    }

}
