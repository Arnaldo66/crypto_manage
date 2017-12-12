<?php

namespace AppBundle\Tests\Integration;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ArticleTest extends WebTestCase
{
    private $client;

    public function setUp(){
      $this->client = static::createClient(array(), array(
          'PHP_AUTH_USER' => 'superadmin',
          'PHP_AUTH_PW'   => 'password',
      ));
    }

    public function testCreateArticle(){
      $crawler = $this->client->request('GET', '/egbo/articles/new');
      $this->buttonCrawler = $crawler->selectButton('btn-create-article');

      $form = $this->buttonCrawler->form(array(
          'article[title]'  => 'titre',
          'article[subtitle]'  => 'sous titre',
          'article[description]'  => 'descr',
          'article[metaDescription]'  => 'metadescr',
      ));
      $this->client->submit($form);
      $this->assertTrue($this->client->getResponse()->isRedirect('/articles'));
      $crawler = $this->client->followRedirect();
      $this->assertCount(1, $crawler->filter('div.alert-success'));
    }

    public function testCreateArticleNoTitle(){
      $crawler = $this->client->request('GET', '/egbo/articles/new');
      $this->buttonCrawler = $crawler->selectButton('btn-create-article');

      $form = $this->buttonCrawler->form(array(
          'article[title]'  => '',
          'article[subtitle]'  => '',
          'article[description]'  => '',
          'article[metaDescription]'  => '',
      ));
      $crawler = $this->client->submit($form);
      $this->assertCount(4, $crawler->filter('span.error'));
    }
}
