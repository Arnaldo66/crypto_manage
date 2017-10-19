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
    private $em;
    private $value;

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
      $currencies = $this->em->getRepository('AppBundle:Currency')->findBy(array('history'=> NULL),array(), 2);
      foreach ($currencies as $key => $value) {
        $this->value = $value;
        $this->createHistory($value);
      }
    }

    private function createHistory($value){
        $client = new Client();
        $crawler = $client->request('GET', $this->getUrl($value->getSlug()));

        $crawler->filter('div#historical-data tr')->each(function ($node,$k) {
            if($k != 0){
              $data = $this->formatStrucToEM($node);
              var_dump($array_tr);die();
            }
        });
    }

    private function formatStrucToEM($node){
      $data = array();
      $data = $this->getDataFromNode($node);
      $data['day'] = $this->createDateTime($data[0],$data[1],$data[2]);
      $data['currency'] = $this->value;
      if(!$this->canProcess($data)){
        die('ii');
      }else{
        die('no');
      }
    }

    private function canProcess($data){
      //TODO: have to be excatl date and no dateTime
      $entity = $this->em->getRepository('AppBundle:CurrencyValueHistory')->findOneBy(
        array('day'=>'2017-01-01', 'currency'=>$data['currency'])
      );

      var_dump($entity === NULL);die();
    }

    private function getDataFromNode($node){
      $cleanValue = preg_replace('!\s+!', ' ', $node->text());
      return explode(' ',$cleanValue);
    }

    private function getUrl($slug){
      return 'https://coinmarketcap.com/currencies/'.$slug.'/historical-data/?start=20110101&end='.date('Y').date('m').date('d');
    }

    private function createDateTime($month, $day, $year){
      $date = \DateTime::createFromFormat('j-M-Y', str_replace(',','',$day).'-'.$month.'-'.$year);
      $string_date = $date->format('Y-m-d');
      return $string_date;
    }

}
