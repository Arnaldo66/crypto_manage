<?php
namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use AppBundle\Entity\Currency;
use AppBundle\Entity\CurrencyValueMoment;
use AppBundle\Entity\CurrencyValueDay;

class CreateCurrencyValueMomentCommand extends ContainerAwareCommand
{
    //TODO: have to transfert data in other table before truncate
    // add validation verification before flush entity

    protected function configure()
    {
      $this
        ->setName('cron:create-currency-value-moment')
        ->setDescription('Fill database with details of best currency in the market at the moment')
        ->setHelp('This command fill table currency_value_moment. Launch by cron 1 time by 5 minutes.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
      $client = new \GuzzleHttp\Client();
      $res = $client->request('GET', 'https://api.coinmarketcap.com/v1/ticker/?convert=EUR&limit=100');
      $em = $this->getContainer()->get('doctrine')->getManager();

      //insert old value in table currencyValueDay
      $this->createCurrencyValueDay($em);

      //destroy all value
      $this->truncateTable($em);

      $output_message = 'KO';
      //test if the request is valid and with good content
      if($res->getStatusCode() == '200' && $res->getHeaderLine('content-type') == 'application/json'){
          $body = json_decode($res->getBody());
          foreach ($body as $key => $value) {
            $this->createCurrencyValueMoment($em,$value);
          }
          $output_message = 'OK';
      }

      $output->writeln($output_message);
    }

    //insert into CurrencyValueDay
    private function createCurrencyValueDay($em){
      $momentValues = $em->getRepository('AppBundle:CurrencyValueMoment')->findAll();
      foreach ($momentValues as $momentValue) {
        $currencyValueDay = new CurrencyValueDay;
        $currencyValueDay->setCurrency($momentValue->getCurrency());
        $currencyValueDay->setPriceUsd($momentValue->getPriceUsd());
        $currencyValueDay->setPriceBtc($momentValue->getPriceBtc());
        $currencyValueDay->setPriceEur($momentValue->getPriceEur());

        $em->persist($currencyValueDay);
      }

      $em->flush();
    }


    //truncate table currency_moment_value
    private function truncateTable($em){
      $connection = $em->getConnection();
      $platform   = $connection->getDatabasePlatform();
      $connection->executeUpdate($platform->getTruncateTableSQL('currency_value_moment', true));
    }

    //Create new Currency if not exist. Base on currency name
    private function createCurrencyValueMoment($em,$value){
        $currency = $em->getRepository('AppBundle:Currency')->findOneByName($value->name);

        //create currency if not exists
        if($currency === NULL){
          $currency = new Currency;
          $currency->setName($value->name);
          $currency->setSymbol($value->symbol);
          $em->persist($currency);
        }

        $currencyValueMoment = new CurrencyValueMoment;

        $currencyValueMoment->setCurrency($currency);
        $currencyValueMoment->setRank($value->rank);
        $currencyValueMoment->setPriceUsd($value->price_usd);
        $currencyValueMoment->setPriceBtc($value->price_btc);
        $currencyValueMoment->setMarketCapUsd($value->market_cap_usd);
        $currencyValueMoment->setAvailableSupply($value->available_supply);
        $currencyValueMoment->setTotalSupply($value->total_supply);
        $currencyValueMoment->setPercentChange1h($value->percent_change_1h);
        $currencyValueMoment->setPercentChange24h($value->percent_change_24h);
        $currencyValueMoment->setPercentChange7d($value->percent_change_7d);
        $currencyValueMoment->setLastUpdated($value->last_updated);
        $currencyValueMoment->setPriceEur($value->price_eur);
        $currencyValueMoment->setMarketCapEur($value->percent_change_24h);

        $em->persist($currencyValueMoment);
        $em->flush();
    }
}