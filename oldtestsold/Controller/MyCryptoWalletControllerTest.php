<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class MyCryptoWalletControllerTest extends WebTestCase
{
    private $client;

    public function setUp()
    {
        $this->client = static::createClient(array(), array(
          'PHP_AUTH_USER' => 'username',
          'PHP_AUTH_PW'   => 'password',
        ));
    }

    public function testIndex()
    {
        $this->client->request('GET', '/u/my-crypto/dashboard');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    public function testNew()
    {
        $this->client->request('GET', '/u/my-crypto/new');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    public function testShow()
    {
        $this->client->request('GET', '/u/my-crypto/1');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }
}
