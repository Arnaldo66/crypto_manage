<?php

namespace App\Command;

use App\Entity\Article;
use App\Entity\Currency;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;

class CronSitemapsGeneratorCommand extends Command
{
    private $manager;
    private $params;

    public function __construct(ObjectManager $manager, ParameterBagInterface $params)
    {
        $this->manager = $manager;
        $this->params = $params;

        parent::__construct();
    }


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
        $file = $this->params->get('kernel.project_dir') . '/public/sitemaps.xml';

        $fileSystem = new Filesystem();
        if (!$fileSystem->exists($file)) {
            $fileSystem->touch($file);
        }

        $xml = $this->createXmlData();
        $fileSystem->dumpFile($file, $xml->asXML());

        $output->writeln('OK');
    }

    private function createXmlData()
    {
        $data = $this->xmlHeader();
        $data .= $this->createStaticLink();
        $data .= $this->createDynamicLink();
        $data .= '</urlset>';
        return simplexml_load_string($data);
    }

    private function xmlHeader()
    {
        return '<?xml version="1.0" encoding="UTF-8"?>
              <urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
    }

    private function createStaticLink()
    {
        return "<url><loc>https://e-goldenboy.com/</loc><changefreq>daily</changefreq><priority>1</priority></url>
              <url><loc>https://e-goldenboy.com/tableau-de-bord</loc><changefreq>daily</changefreq><priority>1</priority></url>
              <url><loc>https://e-goldenboy.com/login</loc><changefreq>daily</changefreq><priority>0.9</priority></url>
              <url><loc>https://e-goldenboy.com/register/</loc><changefreq>daily</changefreq><priority>0.9</priority></url>
              <url><loc>https://e-goldenboy.com/contact</loc><changefreq>daily</changefreq><priority>0.9</priority></url>
              <url><loc>https://e-goldenboy.com/portefeuilles-publics</loc><changefreq>daily</changefreq><priority>0.9</priority></url>
              <url><loc>https://www.e-goldenboy.com/toutes-les-crypto-monnaies</loc><changefreq>daily</changefreq><priority>0.9</priority></url>\r";
    }

    private function createDynamicLink()
    {
        $data = '';
        $articles = $this->manager->getRepository(Article::class)->findAll();
        foreach ($articles as $value) {
            $data .= "<url><loc>https://e-goldenboy.com/articles/".$value->getSlug()."</loc><changefreq>daily</changefreq><priority>1</priority></url>\r";
        }

        $currencies = $this->manager->getRepository(Currency::class)->findBy([], ['rank' => 'ASC'], 1000);
        foreach ($currencies as $value) {
            $data .= "<url><loc>https://e-goldenboy.com/crypto-monnaies/".$value->getSlug()."</loc><changefreq>daily</changefreq><priority>1</priority></url>\r";
        }

        return $data;
    }
}
