<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Goutte\Client;

use AppBundle\Entity\CurrencyValueHistory;

class CronGetHistoricalDataCommand extends ContainerAwareCommand
{
    private $em;
    private $value;
    private $insert;

    //TODO: get all 3 minutes, currency history <> true
    // max 2 by cron. Over after 3 day. then no limit but 1 by day
    protected function configure()
    {
        $this
            ->setName('cron:get-historical-data')
            ->setDescription('Get in web site to get historical data')
            ->setHelp('Launch by cron 1 time by 10 minutes until full.');
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
      $this->em = $this->getContainer()->get('doctrine')->getManager();
      $currencies = $this->em->getRepository('AppBundle:Currency')->findBy(
        array('history'=> NULL),array(), 2
      );
      foreach ($currencies as $key => $value) {
        $this->insert = 0;
        $this->value = $value;
        $this->createHistory($value);
        if($this->insert > 0){
          $value->setHistory(1);
        }
      }
      $this->em->flush();
    }

    private function createHistory($value){
        $client = new Client();
        $crawler = $client->request('GET', $this->getUrl($value->getUniqueName()));

        $crawler->filter('div#historical-data tr')->each(function ($node,$k) {
            if($k != 0){
              $data = $this->formatStrucToEM($node);
              if($data !== false){
                $this->insertHistoricalValue($data);
              }
            }
        });
    }

    private function insertHistoricalValue($data){
        $entity = new CurrencyValueHistory;
        $entity->setHightUsd($data['hightUsd']);
        $entity->setLowUsd($data['lowUsd']);
        $entity->setAverageUsd($data['averageUsd']);
        $entity->setHightEur($data['hightEur']);
        $entity->setLowEur($data['lowEur']);
        $entity->setAverageEur($data['averageEur']);
        $entity->setCurrency($data['currency']);
        $entity->setDay($data['day']);

        $this->em->persist($entity);
        $this->insert++;
    }

    private function formatStrucToEM($node){
      $data = array();
      $usd_convert = 0.84570;
      $data_node = $this->getDataFromNode($node);
      $data['day'] = $this->createDateTime($data_node[0],$data_node[1],$data_node[2]);
      $data['currency'] = $this->value;
      if(!$this->canProcess($data)){
        return false;
      }
      $data['hightUsd'] = $data_node[4];
      $data['lowUsd'] = $data_node[5];
      $data['hightEur'] = $data_node[4] * $usd_convert;
      $data['lowEur'] = $data_node[5] * $usd_convert;
      $data['averageUsd'] = (($data_node[4] + $data_node[5]) / 2);
      $data['averageEur'] = (($data_node[4] + $data_node[5]) / 2) * $usd_convert;
      return $data;
    }

    private function canProcess($data){
      $entity = $this->em->getRepository('AppBundle:CurrencyValueHistory')->findOneBy(
        array('day'=>$data['day'], 'currency'=>$data['currency'])
      );

      return $entity === NULL;
    }

    private function getDataFromNode($node){
      $cleanValue = preg_replace('!\s+!', ' ', $node->text());
      return explode(' ',$cleanValue);
    }

    private function getUrl($slug){
      return 'https://coinmarketcap.com/currencies/'.$slug.'/historical-data/?start=20110101&end='.date('Y').date('m').date('d');
    }

    private function createDateTime($month, $day, $year){
      return \DateTime::createFromFormat(
        'j-M-Y H:i:s', str_replace(',','',$day).'-'.$month.'-'.$year.' 00:00:00'
      );
    }
}
