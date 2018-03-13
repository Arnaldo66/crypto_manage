<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ConverterControllerTest extends WebTestCase
{
    public function testNew()
    {
        $client = static::createClient();

        $client->request('GET', '/convertisseur');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}
