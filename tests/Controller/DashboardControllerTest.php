<?php

namespace App\Tests\Controller;

use Symfony\Component\Panther\PantherTestCase;

class DashboardControllerTest extends PantherTestCase
{
    public function testDashboard()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/tableau-de-bord');

        $this->assertResponseIsSuccessful();
    }
}
