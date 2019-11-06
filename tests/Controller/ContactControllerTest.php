<?php

namespace App\Tests\Controller;

use Symfony\Component\Panther\PantherTestCase;

class ContactControllerTest extends PantherTestCase
{
    public function testContact()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/contact');

        $this->assertResponseIsSuccessful();

        $form = $crawler->selectButton('btn-create-contact')->form();
        $form['contact[name]'] = 'Lucas';
        $form['contact[email]'] = 'test@phpunit.fr';
        $form['contact[message]'] = 'Hello boy';

        $crawler = $client->submit($form);
        $this->assertResponseRedirects('/contact', 302);
    }
}
