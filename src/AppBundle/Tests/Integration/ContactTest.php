<?php

namespace AppBundle\Tests\Integration;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ContactTest extends WebTestCase
{
    public function testContact()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/contact');
        $buttonCrawlerNode = $crawler->selectButton('btn-create-contact');

        $form = $buttonCrawlerNode->form(array(
            'contact[name]'  => 'Name',
            'contact[email]'  => 'email@yopmail.com',
            'contact[message]'  => 'Message',
        ));
        $client->submit($form);
        $this->assertTrue($client->getResponse()->isRedirect('/contact'));
        $crawler = $client->followRedirect();
        $this->assertCount(1, $crawler->filter('div.alert-success'));
    }

}
