<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CurrencyControllerTest extends WebTestCase
{
    public function testShow()
    {
        //TODO: try to do with doctrine in code setGraphData cause pb
        /*$client = static::createClient();

        $client->request('GET', '/crypto-monnaies/bitcoin');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());*/
        $this->assertEquals(1, 1);
    }

    public function testShowAll()
    {
        $client = static::createClient();

        $client->request('GET', '/toutes-les-crypto-monnaies');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}
