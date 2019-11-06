<?php
namespace App\Command;

use App\Entity\Currency;
use App\Entity\CurrencyValueDay;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\Entity\CurrencyValueHistory;

class CreateCurrencyValueHistoryCommand extends Command
{
    /**
     * @var ObjectManager
     */
    private $manager;

    public function __construct(ObjectManager $manager)
    {
        $this->manager = $manager;
        parent::__construct();
    }

    protected function configure()
    {
        $this
        ->setName('cron:create-currency-history')
        ->setDescription('Fill database with top, lower et AVG value for the last day')
        ->setHelp('This command fill table currency_value_history. Launch by cron 1 time by day at 00h01.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $repository = $this->manager->getRepository(CurrencyValueDay::class);
        $values = $repository->getHistoricalValueByDay();

        //create historical value
        foreach ($values as $value) {
            $this->createCurrencyValueHistory($this->manager, $value);
        }
        $this->manager->flush();

        //delete this value from currencyDay table
        $repository->truncateTable();

        $output->writeln('ok');
    }

    private function createCurrencyValueHistory($value)
    {
        $currency = $this->manager->getRepository(Currency::class)->find($value['currency_id']);

        $currencyValueHistory = new CurrencyValueHistory;
        $currencyValueHistory->setHightUsd($value['max_usd']);
        $currencyValueHistory->setLowUsd($value['min_usd']);
        $currencyValueHistory->setHightEur($value['max_euro']);
        $currencyValueHistory->setLowEur($value['min_euro']);
        $currencyValueHistory->setAverageUsd($value['avg_usd']);
        $currencyValueHistory->setAverageEur($value['avg_euro']);
        $currencyValueHistory->setDay(new \DateTime($value['date_day']));
        $currencyValueHistory->setVolumeUsd24h($value['avg_usd_volume']);
        $currencyValueHistory->setCurrency($currency);

        $this->manager->persist($currencyValueHistory);
    }
}
