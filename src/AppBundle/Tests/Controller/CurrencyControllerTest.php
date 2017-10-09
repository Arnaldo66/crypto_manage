<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CurrencyControllerTest extends WebTestCase
{
    public function testShow()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/crypto-monnaies/bitcoin');
        $this->assertEquals(200,$client->getResponse()->getStatusCode());
    }

}
