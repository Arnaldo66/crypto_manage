<?php

namespace App\Tests;

use Symfony\Component\Panther\PantherTestCase;
use App\Entity\Currency;
use App\Entity\Alert;


class AlertControllerTest extends PantherTestCase
{
    public function testAlertsWithoutUser()
    {
        $client = static::createClient();
        $client->request('GET', '/u/alerts');

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

    public function testNewAlert()
    {
        $client = static::createClient(
            [],
            [
                'PHP_AUTH_USER' => 'user',
                'PHP_AUTH_PW' => 'phpunit_user'
            ]
        );
        $crawler = $client->request('GET', '/u/alerts/new');
        $this->assertResponseIsSuccessful();

        $manager = $client->getContainer()->get('doctrine');
        $currency = $manager->getRepository(Currency::class)->findOneBySymbol('BTC');

        $form = $crawler->selectButton('btn-create-contact')->form();
        $form['alert[buy]'] = 1;
        $form['alert[price]'] = 1000;
        $form['alert[currency]'] = $currency->getId();

        $client->submit($form);
        $this->assertResponseRedirects('/u/alerts', 302);
    }

    public function testDeleteAlert()
    {
        $client = static::createClient(
            [],
            [
                'PHP_AUTH_USER' => 'user',
                'PHP_AUTH_PW' => 'phpunit_user'
            ]
        );
        $manager = $client->getContainer()->get('doctrine');
        $alert = $manager->getRepository(alert::class)->findOneByBuy(1);
        $client->request('delete', '/u/alert/delete/' . $alert->getId());
        $this->assertResponseRedirects('/u/alerts', 302);
    }
}
