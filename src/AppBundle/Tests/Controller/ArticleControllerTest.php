<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ArticleControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/articles');
        $this->assertEquals(200,$client->getResponse()->getStatusCode());
    }

    public function testShow()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/articles/unique');
        $this->assertEquals(200,$client->getResponse()->getStatusCode());
    }

}
