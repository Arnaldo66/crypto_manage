<?php

namespace AppBundle\Tests\Integration;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ContactTest extends WebTestCase
{
    private $client;
    private $buttonCrawler;

    /**
     * @before
     */
    public function setUpVariable(){
      $this->client = static::createClient();
      $crawler = $this->client->request('GET', '/contact');
      $this->buttonCrawler = $crawler->selectButton('btn-create-contact');
    }

    /**
     * validate that is false redirect
     */
     public function validateFalseRedirect($form){
         $this->client->submit($form);
         $this->assertFalse($this->client->getResponse()->isRedirect('/contact'));
     }


    public function testContact()
    {
        $form = $this->buttonCrawler->form(array(
            'contact[name]'  => 'Name',
            'contact[email]'  => 'email@yopmail.com',
            'contact[message]'  => 'Message',
        ));
        $this->client->submit($form);
        $this->assertTrue($this->client->getResponse()->isRedirect('/contact'));
        $crawler = $this->client->followRedirect();
        $this->assertCount(1, $crawler->filter('div.alert-success'));
    }

    public function testwithEmptyName()
    {
        $form = $this->buttonCrawler->form(array(
            'contact[name]'  => '',
            'contact[email]'  => 'email@yopmail.com',
            'contact[message]'  => 'Message',
        ));
        $this->validateFalseRedirect($form);
    }

    public function testwithEmptyEmail()
    {
        $form = $this->buttonCrawler->form(array(
            'contact[name]'  => 'Name',
            'contact[email]'  => '',
            'contact[message]'  => 'Message',
        ));
        $this->validateFalseRedirect($form);
    }

    public function testwithWrongEmail()
    {
        $form = $this->buttonCrawler->form(array(
            'contact[name]'  => 'Name',
            'contact[email]'  => 'thisemailisnotcorrect',
            'contact[message]'  => 'Message',
        ));
        $this->validateFalseRedirect($form);
    }

    public function testwithEmptyMessage()
    {
        $form = $this->buttonCrawler->form(array(
            'contact[name]'  => 'Name',
            'contact[email]'  => 'email@yopmail.com',
            'contact[message]'  => '',
        ));
        $this->validateFalseRedirect($form);
    }

}
