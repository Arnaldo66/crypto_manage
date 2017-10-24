<?php

namespace AppBundle\Tests\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{

    private $client;
    private $forbidden;

    public function setUp(){
      $this->client = static::createClient(array(), array(
          'PHP_AUTH_USER' => 'superadmin',
          'PHP_AUTH_PW'   => 'password',
      ));
    }

    public function testUsers()
    {
      $crawler = $this->client->request('GET', '/egbo/users');
      $this->assertEquals(200,$this->client->getResponse()->getStatusCode());
    }
}
