<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AlertControllerTest extends WebTestCase
{

    private $client;

    public function setUp(){
      exec('php bin/console doctrine:fixtures:load -e test --no-interaction --purge-with-truncate');
      $this->client = static::createClient(array(), array(
          'PHP_AUTH_USER' => 'username',
          'PHP_AUTH_PW'   => 'password',
      ));
    }

    public function testIndex()
    {
        $crawler = $this->client->request('GET', '/user/alerts');
        $this->assertEquals(200,$this->client->getResponse()->getStatusCode());
    }

    public function testNew()
    {
        $crawler = $this->client->request('GET', '/user/alert/new');
        $this->assertEquals(200,$this->client->getResponse()->getStatusCode());
    }

    public function testDelete()
    {
        $crawler = $this->client->request('GET', '/user/alert/delete');
        $this->assertEquals(200,$this->client->getResponse()->getStatusCode());
    }

}
