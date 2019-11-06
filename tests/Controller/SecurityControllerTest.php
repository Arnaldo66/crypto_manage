<?php

namespace App\Tests\Controller;

use App\Entity\User;
use Symfony\Component\Panther\PantherTestCase;

class SecurityControllerTest extends PantherTestCase
{
    public function testLogin()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');

        $this->assertResponseIsSuccessful();

        $form = $crawler->selectButton('_submit')->form();
        $form['_username'] = 'user';
        $form['_password'] = 'phpunit_user';
        $form['_remember_me'] = 'on';

        $client->submit($form);
        $this->assertResponseRedirects('http://localhost/tableau-de-bord', 302);
    }

    public function testRessetingRequest()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/resetting/request');

        $this->assertResponseIsSuccessful();

        $form = $crawler->selectButton('_submit')->form();
        $form['_email'] = 'test@phpunit.fr';

        $client->submit($form);
        $this->assertResponseRedirects('/resetting/done', 302);

        $manager = $client->getContainer()->get('doctrine');
        $token = $manager->getRepository(User::class)->findOneByEmail('test@phpunit.fr')->getTokenResetPassword();


        $crawler = $client->request('GET', '/resetting/request/' . $token);
        $this->assertResponseIsSuccessful();

        $form = $crawler->selectButton('_submit')->form();
        $form['password1'] = 'phpunit_user';
        $form['password2'] = 'phpunit_user';

        $client->submit($form);
        $this->assertResponseIsSuccessful();
    }
}
