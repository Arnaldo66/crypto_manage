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
      $em = $this->getContainer()->get('doctrine')->getEntityManager();
      $currency = new Currency;
      $currency->setName('bitcoion');
      $currency->setSymbol('BTC');
      $em->persist($currency);
      $em->flush();

      $output->writeln('Whoa!');
    }
}
