<?php
namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use AppBundle\Entity\CurrencyValueDay;

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
      $output->writeln('ok');
    }

}
