<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AlertControllerTest extends WebTestCase
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
      $crawler = $client->request('GET', '/u/alerts');

      $this->assertEquals(302,$client->getResponse()->getStatusCode());
    }

    public function testIndex()
    {
        $crawler = $this->client->request('GET', '/u/alerts');
        $this->assertEquals(200,$this->client->getResponse()->getStatusCode());
    }

    public function testNewWithoutUser(){
      $client = static::createClient();
      $crawler = $client->request('GET', '/u/alerts/new');

      $this->assertEquals(302,$client->getResponse()->getStatusCode());
    }

    public function testNew()
    {
        $crawler = $this->client->request('GET', '/u/alerts/new');
        $this->assertEquals(200,$this->client->getResponse()->getStatusCode());
    }

    public function testDeleteWithoutUser(){
      $client = static::createClient();
      $crawler = $client->request('GET', '/u/alerts/delete');

      $this->assertEquals(302,$client->getResponse()->getStatusCode());
    }

}
