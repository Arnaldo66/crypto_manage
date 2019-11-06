<?php

namespace App\Tests\Controller;

use Symfony\Component\Panther\PantherTestCase;

class RegistrationControllerTest extends PantherTestCase
{
    public function testSomething()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/register');

        $this->assertResponseIsSuccessful();

        $form = $crawler->selectButton('create-user')->form();
        $form['user[email]'] = 'functional@phpunit.fr';
        $form['user[username]'] = 'functional';
        $form['user[plainPassword][first]'] = '123456789';
        $form['user[plainPassword][second]'] = '123456789';

        $client->submit($form);
        $this->assertResponseRedirects('/tableau-de-bord', 302);
    }
}
