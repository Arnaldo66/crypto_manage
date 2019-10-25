<?php

namespace App\Tests;

use Symfony\Component\Panther\PantherTestCase;

class AlertControllerTest extends PantherTestCase
{
    public function testAlertsWithoutUser()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/u/alerts');

        $this->assertResponseRedirects('http://localhost/login', 302);
    }

    public function testAlerts()
    {
        $client = static::createClient(
            [],
            [
                'PHP_AUTH_USER' => 'user',
                'PHP_AUTH_PW' => 'phpunit_user'
            ]
        );
        $client->request('GET', '/u/alerts');
        $this->assertResponseIsSuccessful();
    }
}
