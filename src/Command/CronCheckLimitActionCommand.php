<?php

namespace App\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use App\Service\WalletManager;

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
        $em = $this->getContainer()->get('doctrine')->getManager();
        $walletManager = $this->getContainer()->get(WalletManager::class);
        $orderStatus = $this->getContainer()->getParameter('order_status_pending');

        $tradeOrders = $em->getRepository('App:TradingOrder')->findBy(array('orderStatus'=>$orderStatus));
        foreach ($tradeOrders as $tradeOrder) {
            if ($tradeOrder->getPrice() >= $tradeOrder->getCurrency()->getPriceEur()) {
                $walletManager->finaliseOrder($tradeOrder, 1);
            }
        }
        $output->writeln('OK');
    }
}
