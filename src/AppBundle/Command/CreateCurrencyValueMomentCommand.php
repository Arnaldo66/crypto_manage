<?php
namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;

use AppBundle\Entity\Currency;
use AppBundle\Entity\Alert;
use AppBundle\Entity\CurrencyValueMoment;
use AppBundle\Entity\UpdatePriceLogs;

class CreateCurrencyValueMomentCommand extends ContainerAwareCommand
{
    // TODO: get image when not exists
    private $client;
    private $devise = ['usd', 'eur', 'btc'];
    private $pageLimit = 5;


    // add validation verification before flush entity
    protected function configure()
    {
        $this
        ->setName('cron:update-coin-value')
        ->setDescription('Fill database with details of best currency in the market at the moment')
        ->setHelp('This command fill table currency_value_moment. Launch by cron 1 time by 5 minutes.');

        $this->client = new \GuzzleHttp\Client();
    }

    //TODO: after some day refactor this class and put try catch bloc
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $entityManager = $this->getContainer()->get('doctrine')->getManager();
        //log one import OK by day
        //$entity = $this->beginLog($entityManager);
        $this->truncateTable($entityManager);
        $this->resetRank($entityManager);
        $this->doProcessUpdatePrice($entityManager);
        //$this->endLog($entityManager, $entity);
        $output->writeln('ok');
    }

    private function doProcessUpdatePrice($entityManager)
    {
        for ($page = 1; $page <= $this->pageLimit; $page++) {
            foreach ($this->devise as $devise) {
                $allCoinsRequest = $this->client->request(
                    'GET',
                    'https://api.coingecko.com/api/v3/coins/markets?vs_currency='. $devise .'&page=' . $page
                );
                if ($allCoinsRequest->getStatusCode() == '200') {
                    $allCoins = json_decode($allCoinsRequest->getBody());
                    foreach ($allCoins as $key => $coin) {
                        $this->createCurrencyValueMoment($entityManager, $coin, $devise);
                    }
                    $entityManager->flush();
                }
            }
            $entityManager->flush();
        }

        $alerts = $entityManager->getRepository(Alert::class)->findAll();
        foreach ($alerts as $alert) {
            $priceEuro = $alert->getCurrency()->getPriceEur();
            if (($priceEuro <= $alert->getPrice() && $alert->getBuy()) ||
                ($priceEuro >= $alert->getPrice() && !$alert->getBuy())) {
                    $alert->setCanDelete(1);
                    $this->sendAlertEmail($alert);
                    $entityManager->remove($alert);
            }
        }
        //remove all can delete alert
        $entityManager->flush();
    }

    //TODO: do better organisation
    private function createCurrencyValueMoment($entityManager, $value, $devise)
    {
        $currency = $entityManager->getRepository(Currency::class)->findOneByUniqueName($value->id);
        if ($currency === null) {
            $currency = new Currency;
            $currency->setName($value->name);
            $currency->setUniqueName($value->id);
            $currency->setSymbol($value->symbol);
            $currency->setPriceUsd($value->current_price);
            $currency->setRank($value->market_cap_rank);

            $entityManager->persist($currency);
        } else {
            if ($devise === 'usd') {
                $currency->setPriceUsd($value->current_price);
            } elseif ($devise === 'eur') {
                $currency->setPriceEur($value->current_price);
            } else {
                $currency->setPriceBtc($value->current_price);
            }
            $currency->setName($value->name);
            $currency->setUniqueName($value->id);
            $currency->setRank($value->market_cap_rank);
            $currency->setMaxSupply($value->total_supply);
            $currency->setCirculatingSupply($value->circulating_supply);
        }

        $currencyValueMoment = $entityManager->getRepository(CurrencyValueMoment::class)
            ->findOneByCurrency($currency);

        if ($currencyValueMoment === null) {
            $currencyValueMoment = new CurrencyValueMoment;
            $currencyValueMoment->setCurrency($currency);
            $currencyValueMoment->setAvailableSupply($value->circulating_supply);
            $currencyValueMoment->setTotalSupply($value->total_supply);
            $currencyValueMoment->setLastUpdated(time());
        }

        $currencyValueMoment->setRank($value->market_cap_rank);

        if ($devise === 'usd') {
            $currencyValueMoment->setMarketCapUsd($value->market_cap);
            $currencyValueMoment->setPercentChange24h($value->price_change_percentage_24h);
            $currencyValueMoment->setPriceUsd($value->current_price);
        } elseif ($devise === 'eur') {
            $currencyValueMoment->setPriceEur($value->current_price);
            $currencyValueMoment->setMarketCapEur($value->market_cap);
        } else {
            $currencyValueMoment->setPriceBtc($value->current_price);
        }

        $entityManager->persist($currencyValueMoment);
    }


    private function beginLog($entityManager)
    {
        $dateDay = new \DateTime();
        $entity = $entityManager->getRepository(UpdatePriceLogs::class)->findOneBy(
            [
                'success' => true,
                'createdAt' => $dateDay
            ]
        );

        if ($entity === null) {
            $entity = new UpdatePriceLogs();
            $entity->setCreatedAt($dateDay);
            $entity->setSuccess(false);

            $entityManager->persist($entity);
            $entityManager->flush();
        }

        return $entity;
    }

    private function endLog($entityManager, $entity)
    {
        $entity->setSuccess(true);
        $entityManager->flush();
    }

    private function truncateTable($entityManager)
    {
        $connection = $entityManager->getConnection();
        $platform   = $connection->getDatabasePlatform();
        $connection->executeUpdate($platform->getTruncateTableSQL('currency_value_moment', true));
    }

    private function resetRank($entityManager)
    {
        $connection = $entityManager->getConnection();
        $query = (' update currency set rank = 10000
                    where not exists(
                        select * from currency_value_moment where currency.id = currency_value_moment.currency_id)
        ');
        $connection->executeUpdate($query);
    }

    /**
     * send email to user for alert
     */
    private function sendAlertEmail($alert)
    {
        $mailer = $this->getContainer()->get('mailer');
        $type = "Achat";
        if ($alert->getBuy() == 0) {
            $type = "Vente";
        }
        $message = (new \Swift_Message('Une alerte a été soulevé'))
           ->setFrom('admin@e-goldenboy.com')
           ->setTo($alert->getUser()->getEmail())
           ->setBody(
               'Message: Une alerte a été soulevé<br/>
               Type: '.$type.'<br/>
               Crypto-monnaie: '.$alert->getCurrency()->getName().'<br/>
               Seuil souhaité: '.$alert->getPrice().'<br/>
               Valeur actuelle: '.$alert->getCurrency()->getPriceEur(),
               'text/html'
           );
        $mailer->send($message);
    }
}
