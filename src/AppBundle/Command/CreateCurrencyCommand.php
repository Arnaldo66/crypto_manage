<?php
namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use AppBundle\Entity\Currency;

class CreateCurrencyCommand extends ContainerAwareCommand
{

    protected function configure()
    {
      $this
        ->setName('cron:create-currency')
        ->setDescription('Fill database with best 100 currency in the market at the moment')
        ->setHelp('This command fill table currency with best currency. Launch by cron 1 time by week');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

      $client = new \GuzzleHttp\Client();
      $res = $client->request('GET', 'https://api.coinmarketcap.com/v1/ticker/?convert=EUR&limit=100');
      $em = $this->getContainer()->get('doctrine')->getManager();

      $output_message = 'KO';
      //test if the request is valid and with good content
      if($res->getStatusCode() == '200' && $res->getHeaderLine('content-type') == 'application/json'){
          $body = json_decode($res->getBody());
          foreach ($body as $key => $value) {
            $this->createCurrency($em,$value);
          }
          $output_message = 'OK';
      }

      $output->writeln($output_message);
    }

    //Create new Currency if not exist. Base on currency name
    private function createCurrency($em,$value){
      if($em->getRepository('AppBundle:Currency')->findOneByName($value->name) === NULL){
        $currency = new Currency;
        $currency->setName($value->name);
        $currency->setSymbol($value->symbol);
        $em->persist($currency);
        $em->flush();
      }
    }
}
