<?php

namespace App\Command;

use App\Entity\TradingOrder;
use Symfony\Component\Console\Command\Command;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\Service\WalletManager;

class CronCheckLimitActionCommand extends Command
{
    private $manager;
    private $walletManager;
    private $orderStatusPending =  2;

    public function __construct(ObjectManager $manager, WalletManager $walletManager)
    {
        $this->manager = $manager;
        $this->walletManager = $walletManager;

        parent::__construct();
    }


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
        $tradeOrders = $this->manager->getRepository(TradingOrder::class)->findBy([
            'orderStatus' => $this->orderStatusPending
        ]);
        foreach ($tradeOrders as $tradeOrder) {
            if ($tradeOrder->getPrice() >= $tradeOrder->getCurrency()->getPriceEur()) {
                $this->walletManager->finaliseOrder($tradeOrder, 1);
            }
        }
        $output->writeln('OK');
    }
}
