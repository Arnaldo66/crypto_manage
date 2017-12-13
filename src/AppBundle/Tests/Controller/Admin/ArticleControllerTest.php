<?php

namespace AppBundle\Tests\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ArticleControllerTest extends WebTestCase
{
    private $client;

    public function setUp(){
      $this->client = static::createClient(array(), array(
          'PHP_AUTH_USER' => 'superadmin',
          'PHP_AUTH_PW'   => 'password',
      ));
    }

    public function testNewArticle()
    {
        $crawler = $this->client->request('GET', '/egbo/articles/new');
        $this->assertEquals(200,$this->client->getResponse()->getStatusCode());
    }

    public function testUpdateArticle()
    {
        $crawler = $this->client->request('GET', '/egbo/articles/1');
        $this->assertEquals(200,$this->client->getResponse()->getStatusCode());
    }

}
