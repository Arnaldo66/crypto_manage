<?php

namespace AppBundle\Tests\Integration;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class MyCryptoWalletTest extends WebTestCase
{
    private $client;
    private $buttonCrawler;

    /**
     * @before
     */
    public function setUpVariable(){
      $this->client = static::createClient(array(), array(
          'PHP_AUTH_USER' => 'username',
          'PHP_AUTH_PW'   => 'password',
      ));
      $crawler = $this->client->request('GET', 'u/my-crypto/new');
      $this->buttonCrawler = $crawler->selectButton('btn-create-contact');
    }

    public function testCreateWallet()
    {
        $form = $this->buttonCrawler->form(array(
            'my_crypto_wallet[name]'  => 'Name'
        ));
        $this->client->submit($form);
        $this->assertTrue($this->client->getResponse()->isRedirect('/u/my-crypto/dashboard'));
        $crawler = $this->client->followRedirect();
        $this->assertCount(1, $crawler->filter('div.alert-success'));
    }

    public function testWrongWallet()
    {
        $form = $this->buttonCrawler->form(array(
            'my_crypto_wallet[name]'  => ''
        ));
        $crawler = $this->client->submit($form);
        $this->assertFalse($this->client->getResponse()->isRedirect('/u/my-crypto/dashboard'));
        $this->assertCount(1, $crawler->filter('span.error'));
    }
}
