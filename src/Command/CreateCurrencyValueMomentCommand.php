<?php

namespace App\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;

use App\Entity\Currency;
use App\Entity\Alert;
use App\Entity\CurrencyValueMoment;
use App\Entity\UpdatePriceLogs;

class CreateCurrencyValueMomentCommand extends ContainerAwareCommand
{
    // TODO: get image when not exists
    private $client;
    private $devise = ['usd', 'eur', 'btc'];
    private $pageLimit = 5;
    private $indexNewImg = 0;


    /**
     * set Guzzle client
     */
    protected function configure()
    {
        $this
        ->setName('cron:update-coin-value')
        ->setDescription('Fill database with details of best currency in the market at the moment')
        ->setHelp('This command fill table currency_value_moment. Launch by cron 1 time by 5 minutes.');

        $this->client = new \GuzzleHttp\Client();
    }

    /**
     * @param  InputInterface  $input
     * @param  OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $entityManager = $this->getContainer()->get('doctrine')->getManager();
        $this->truncateTable($entityManager);
        $this->resetRank($entityManager);
        $this->doProcessUpdatePrice($entityManager);
        $this->checkAlertReady($entityManager);
        $output->writeln('ok');
    }

    /**
     * Call coingecko api to update price or create new coin
     * @param $entityManager
     */
    private function doProcessUpdatePrice($entityManager): void
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
    }

    /**
     * Check alert and send email when alert is ready
     * @param $entityManager
     */
    private function checkAlertReady($entityManager): void
    {
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

        if ($this->indexNewImg < 20 && $currency->getImage() === null) {
            $currency->setImage($this->createImage($currency));
            $this->indexNewImg++;
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

    /**
     * Delete all data in currencyValueMoement before recreate it
     * @param  $entityManager
     */
    private function truncateTable($entityManager): void
    {
        $connection = $entityManager->getConnection();
        $platform   = $connection->getDatabasePlatform();
        $connection->executeUpdate($platform->getTruncateTableSQL('currency_value_moment', true));
    }

    /**
     * Put all data in rank to reorganize with api
     * @param $entityManager
     */
    private function resetRank($entityManager): void
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
    private function sendAlertEmail($alert): void
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


    /**
     * Create Image from api
     * @param  [type] $value [description]
     */
    private function createImage($value): string
    {
        try {
            $result = $this->client->request(
                'GET',
                'https://api.coingecko.com/api/v3/coins/' . $value->getUniqueName() .
                '?tickers=false&market_data=false&community_data=false&developer_data=false&sparkline=false'
            );

            if ($result->getStatusCode() == '200') {
                $result = json_decode($result->getBody());
                $result = $result->image;
            }

            $size = ['32','64'];
            $folder = $this->getContainer()->get('kernel')->getRootDir().'/../web/images/currency-logo';
            $this->createLogoFolder($folder, $size);
            $filename = $value->getUniqueName() .'.png';

            $new_folder = $folder .'/32';
            file_put_contents(
                $new_folder.'/'.$filename,
                file_get_contents($result->thumb)
            );

            $new_folder = $folder .'/64';
            file_put_contents(
                $new_folder.'/'.$filename,
                file_get_contents($result->small)
            );
        } catch (\Exception $execption) {
            return null;
        }


        return $filename;
    }

    //create folder
    private function createLogoFolder($folder, $size)
    {
        $fs = new Filesystem();
        foreach ($size as $value) {
            $new_folder = $folder .'/'. $value;
            try {
                if (!$fs->exists($new_folder)) {
                    $fs->mkdir($new_folder);
                }
            } catch (IOExceptionInterface $e) {
                throw new \Exception("An error occurred while creating your directory at ".$e->getPath());
            }
        }
    }
}
