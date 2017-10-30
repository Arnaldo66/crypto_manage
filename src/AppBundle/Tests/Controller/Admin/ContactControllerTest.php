<?php

namespace AppBundle\Tests\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ContactControllerTest extends WebTestCase
{
    private $client;

    public function setUp(){
      $this->client = static::createClient(array(), array(
          'PHP_AUTH_USER' => 'superadmin',
          'PHP_AUTH_PW'   => 'password',
      ));
    }

    public function testContacts()
    {
        $crawler = $this->client->request('GET', '/egbo/contacts');
        $this->assertEquals(200,$this->client->getResponse()->getStatusCode());
    }

}