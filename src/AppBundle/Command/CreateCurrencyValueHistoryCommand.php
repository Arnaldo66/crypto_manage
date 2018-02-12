<?php
namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use AppBundle\Entity\CurrencyValueHistory;

class CreateCurrencyValueHistoryCommand extends ContainerAwareCommand
{
    protected function configure()
    {
      $this
        ->setName('cron:create-currency-history')
        ->setDescription('Fill database with top, lower et AVG value for the last day')
        ->setHelp('This command fill table currency_value_history. Launch by cron 1 time by day at 00h01.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
      $em = $this->getContainer()->get('doctrine')->getManager();
      $curday_repo = $em->getRepository('AppBundle:CurrencyValueDay');
      $values = $curday_repo->getHistoricalValueByDay();
      
      //create historical value
      foreach ($values as $value) {
        $this->createCurrencyValueHistory($em,$value);
      }
      $em->flush();

      //delete this value from currencyDay table
      $curday_repo->deleteOldValues();

      $output->writeln('ok');
    }

    private function createCurrencyValueHistory($em,$value){
        $currency = $em->getRepository('AppBundle:Currency')->find($value['currency_id']);

        $currencyValueHistory = new CurrencyValueHistory;
        $currencyValueHistory->setHightUsd($value['max_usd']);
        $currencyValueHistory->setLowUsd($value['min_usd']);
        $currencyValueHistory->setHightEur($value['max_euro']);
        $currencyValueHistory->setLowEur($value['min_euro']);
        $currencyValueHistory->setAverageUsd($value['avg_usd']);
        $currencyValueHistory->setAverageEur($value['avg_euro']);
        $currencyValueHistory->setDay(new \DateTime($value['date_day']));
        $currencyValueHistory->setCurrency($currency);

        $em->persist($currencyValueHistory);
    }
}
