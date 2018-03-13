<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RedirectionControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();

        $client->request('GET', '/a-propos');
        $this->assertEquals(301, $client->getResponse()->getStatusCode());
        $this->assertTrue(
            $client->getResponse()->isRedirect('/articles/a-propos')
        );
    }

    public function testFirstStep()
    {
        $client = static::createClient();

        $client->request('GET', '/premiers-pas');
        $this->assertEquals(301, $client->getResponse()->getStatusCode());
        $this->assertTrue(
            $client->getResponse()->isRedirect(
                '/articles/explication-du-jeu-de-simulation-de-trading'
            )
        );
    }

    public function testWhereBuy()
    {
        $client = static::createClient();

        $client->request('GET', '/ou-acheter-des-bitcoins');
        $this->assertEquals(301, $client->getResponse()->getStatusCode());
        $this->assertTrue(
            $client->getResponse()->isRedirect(
                '/articles/ou-acheter-vendre-des-bitcoins'
            )
        );
    }
}
