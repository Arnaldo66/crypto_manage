<?php

namespace AppBundle\Tests\Integration;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockFileSessionStorage;

class MyCryptoDetailTest extends WebTestCase
{
    private $client;
    const GOOD_WALLET = '1';
    const MARKET_METHOD = '1';
    const LIMIT_METHOD = '1';
    const NO_MONNEY_WALLET = '2';

    public function setUp()
    {
        $this->client = static::createClient(array(), array(
          'PHP_AUTH_USER' => 'username',
          'PHP_AUTH_PW'   => 'password',
        ));
    }

    public function testCreateWalletDetail()
    {
        $crawler = $this->client->request('GET', '/u/my-crypto-detail/1/new');
        $buttonNode = $crawler->selectButton('btn-add-crypto');
        $form = $buttonNode->form(array(
            'my_crypto_wallet_detail[amount]' => '1',
            'my_crypto_wallet_detail[currency]'  => '1',
        ));
        $crawler = $this->client->submit($form);
        $this->assertTrue($this->client->getResponse()->isRedirect('/u/my-crypto/1'));
        $crawler = $this->client->followRedirect();
        $this->assertCount(1, $crawler->filter('div.alert-success'));

        return $crawler;
    }

    public function testDeleteWalletDetail()
    {
        $this->client->request('GET', '/u/my-crypto-detail/delete/1');
        $this->assertTrue($this->client->getResponse()->isRedirect('/u/my-crypto/1'));
        $crawler = $this->client->followRedirect();
        $this->assertCount(1, $crawler->filter('div.alert-success'));

        return $crawler;
    }
}
