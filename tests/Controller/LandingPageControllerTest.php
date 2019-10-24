<?php

namespace App\Tests\Controller;

use Symfony\Component\Panther\PantherTestCase;

class LandingPageControllerTest extends PantherTestCase
{
    public function testLandingPage()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $this->assertResponseIsSuccessful();
    }
}
