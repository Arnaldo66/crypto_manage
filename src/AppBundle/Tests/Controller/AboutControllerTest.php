<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AboutControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/a-propos');
        $this->assertEquals(200,$client->getResponse()->getStatusCode());
    }

    public function testFirstStep()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/premiers-pas');
        $this->assertEquals(200,$client->getResponse()->getStatusCode());
    }

    public function testWhereBuy()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/ou-acheter-des-bitcoins');
        $this->assertEquals(200,$client->getResponse()->getStatusCode());
    }

}
