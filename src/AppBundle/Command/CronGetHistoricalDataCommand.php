<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Goutte\Client;

class CronGetHistoricalDataCommand extends ContainerAwareCommand
{
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
      $client = new Client();
      $crawler = $client->request('GET', 'https://coinmarketcap.com/currencies/bitcoin/historical-data/?start=20130928&end=20171018');

      $crawler->filter('div#historical-data tr')->each(function ($node,$k) {
          if($k != 0){
            $cleanValue = preg_replace('!\s+!', ' ', $node->text());
            $array_tr = explode(' ',$cleanValue);
            $date = $this->createDateTime($array_tr[0],$array_tr[1],$array_tr[2]);
            var_dump($array_tr);die();
          }
      });
    }

    private function createDateTime($month, $day, $year){
      $date = \DateTime::createFromFormat('j-M-Y', str_replace(',','',$day).'-'.$month.'-'.$year);
      var_dump($date);die();
    }

}
