<?php

namespace App\Tests\Controller;

use Symfony\Component\Panther\PantherTestCase;

class ArticleControllerTest extends PantherTestCase
{
    public function testIndex()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/articles');

        $this->assertResponseIsSuccessful();
    }

    public function testShow()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/articles/test');

        $this->assertResponseIsSuccessful();
    }
}
