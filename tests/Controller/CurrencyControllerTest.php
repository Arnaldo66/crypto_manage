<?php

namespace App\Tests\Controller;

use Symfony\Component\Panther\PantherTestCase;

class CurrencyControllerTest extends PantherTestCase
{
    public function testCurrencies()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/toutes-les-crypto-monnaies');

        $this->assertResponseIsSuccessful();
    }

    public function testShow()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', 'crypto-monnaies/bitcoin');

        $this->assertResponseIsSuccessful();
    }
}
