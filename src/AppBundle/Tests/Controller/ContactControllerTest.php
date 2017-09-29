<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ContactControllerTest extends WebTestCase
{

    public function testNew()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/contact');
        $this->assertEquals(200,$client->getResponse()->getStatusCode());
    }

}