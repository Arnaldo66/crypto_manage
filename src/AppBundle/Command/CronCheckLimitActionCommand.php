<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class CronCheckLimitActionCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('cron:check-limit-action')
            ->setDescription('Check if limit order can be finalised')
            ->setHelp('Launch by cron 1 time by 11 minutes.');
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('OK');
    }

}
