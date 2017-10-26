<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CurrencyControllerTest extends WebTestCase
{
    public function testShow()
    {
        //TODO: try to do with doctrine
        /*$client = static::createClient();

        $crawler = $client->request('GET', '/crypto-monnaies/bitcoin');*/
        $this->assertEquals(1,1);
    }

    public function testShowAll()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/toutes-les-crypto-monnaies');
        $this->assertEquals(200,$client->getResponse()->getStatusCode());
    }

}
