<?php

namespace App\Command;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use App\Entity\Currency;
use App\Entity\Alert;
use App\Entity\CurrencyValueMoment;

class CreateCurrencyValueMomentCommand extends Command
{
    private $client;
    private $devise = ['usd', 'eur', 'btc'];
    private $pageLimit = 5;
    private $indexNewImg = 0;
    private $manager;
    private $params;
    private $mailer;


    public function __construct(ObjectManager $manager, ParameterBagInterface $params, \Swift_Mailer $mailer)
    {
        $this->manager = $manager;
        $this->params = $params;
        $this->mailer = $mailer;

        parent::__construct();
    }

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
        $this->truncateTable($this->manager);
        $this->resetRank($this->manager);
        $this->doProcessUpdatePrice($this->manager);
        $this->checkAlertReady($this->manager);
        $output->writeln('ok');
    }

    /**
     * Call coingecko api to update price or create new coin
     * @param $entityManager
     */
    private function doProcessUpdatePrice(ObjectManager $entityManager): void
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
    private function checkAlertReady(ObjectManager $entityManager): void
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
    private function createCurrencyValueMoment(ObjectManager $entityManager, $value, $devise)
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
    private function truncateTable(ObjectManager $entityManager): void
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
        $this->mailer->send($message);
    }


    /**
     * @param $value
     *
     * @return string|null
     */
    private function createImage($value): ?string
    {
        try {
            $result = $this->client->request(
                'GET',
                'https://api.coingecko.com/api/v3/coins/' . $value->getUniqueName() .
                '?tickers=false&market_data=false&community_data=false&developer_data=false&sparkline=false'
            );

            if ($result->getStatusCode() == '200') {
                $result = json_decode($result->getBody());
                $result = json_decode(json_encode($result->image), true);
            }

            $formats = ['thumb','small', 'large'];
            $folder = $this->params->get('kernel.project_dir') . '/public/images/coin-logo';
            $this->createLogoFolder($formats, $folder);
            $filename = $value->getUniqueName() .'.png';
            foreach ($formats as $format) {
                $formatFolder = $folder . '/' . $format;
                file_put_contents(
                    $formatFolder.'/'.$filename,
                    file_get_contents($result[$format])
                );
            }

        } catch (\Exception $exception) {
            return null;
        }


        return $filename;
    }

    /**
     * @param array  $format
     * @param string $folder
     *
     * @throws \Exception
     */
    private function createLogoFolder(array $format, string $folder): void
    {
        $filesystem = new Filesystem();


        try {
            if(!$filesystem->exists($folder)) {
                $filesystem->mkdir($folder);
            }
        } catch (IOExceptionInterface $exception) {
            throw new \Exception("An error occurred while creating your directory at ".$exception->getPath());
        }

        foreach ($format as $value) {
            $formatFolder = $folder .'/'. $value;
            if (!$filesystem->exists($formatFolder)) {
                try {
                    $filesystem->mkdir($formatFolder);
                } catch (IOExceptionInterface $exception) {
                    throw new \Exception("An error occurred while creating your directory at ".$exception->getPath());
                }
            }
        }
    }
}
