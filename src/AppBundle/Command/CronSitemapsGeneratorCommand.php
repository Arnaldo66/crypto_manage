<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;

class CronSitemapsGeneratorCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('cron:sitemaps-generator')
            ->setDescription('Generate sitemaps from currencies data')
            ->setHelp('Launch by cron 1 time by day. 2h00');
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine')->getManager();
        $file = $this->getContainer()->get('kernel')->getRootDir().'/../web/sitemaps.xml';

        $fs = new Filesystem();
        if(!$fs->exists($file)){
          $fs->touch($file);
        }

        $xml = $this->createXmlData($em);
        $fs->dumpFile($file, $xml->asXML());

        $output->writeln('Command result.');
    }

    private function createXmlData($em){
      $data = $this->xmlHeader();
      $data .= $this->createStaticLink();
      $data .= $this->createDynamicLink($em);
      $data .= '</urlset>';
      return simplexml_load_string($data);
    }

    private function xmlHeader(){
      return '<?xml version="1.0" encoding="UTF-8"?>
              <urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
    }

    private function createStaticLink(){
      return "<url><loc>https://e-goldenboy.com/</loc><changefreq>daily</changefreq><priority>1</priority></url>
              <url><loc>https://e-goldenboy.com/dashboard</loc><changefreq>daily</changefreq><priority>1</priority></url>
              <url><loc>https://e-goldenboy.com/login</loc><changefreq>daily</changefreq><priority>0.9</priority></url>
              <url><loc>https://e-goldenboy.com/register/</loc><changefreq>daily</changefreq><priority>0.9</priority></url>
              <url><loc>https://e-goldenboy.com/convertisseur</loc><changefreq>daily</changefreq><priority>0.9</priority></url>
              <url><loc>https://e-goldenboy.com/contact</loc><changefreq>daily</changefreq><priority>0.9</priority></url>
              <url><loc>https://e-goldenboy.com/premiers-pas</loc><changefreq>daily</changefreq><priority>0.9</priority></url>
              <url><loc>https://e-goldenboy.com/portefeuilles-publics</loc><changefreq>daily</changefreq><priority>0.9</priority></url>
              <url><loc>https://www.e-goldenboy.com/toutes-les-crypto-monnaies</loc><changefreq>daily</changefreq><priority>0.9</priority></url>\r";
    }

    private function createDynamicLink($em){
      $data = '';
      $articles = $em->getRepository('AppBundle:Article')->findAll();
      foreach ($articles as $value) {
        $data .= "<url><loc>https://e-goldenboy.com/articles/".$value->getSlug()."</loc><changefreq>daily</changefreq><priority>1</priority></url>\r";
      }

      $currencies = $em->getRepository('AppBundle:Currency')->findAll();
      foreach ($currencies as $value) {
        $data .= "<url><loc>https://e-goldenboy.com/crypto-monnaies/".$value->getSlug()."</loc><changefreq>daily</changefreq><priority>1</priority></url>\r";
      }

      return $data;
    }

}
