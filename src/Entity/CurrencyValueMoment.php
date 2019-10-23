<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * CurrencyValueMoment
 *
 * @ORM\Table(name="currency_value_moment")
 * @ORM\Entity(repositoryClass="App\Repository\CurrencyValueMomentRepository")
 */
class CurrencyValueMoment
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var int
     *
     * @Assert\NotBlank()
     * @ORM\Column(name="rank", type="integer")
     */
    private $rank;

    /**
     * @var float
     *
     * @ORM\Column(name="price_usd", type="float", nullable=true)
     */
    private $priceUsd;

    /**
     * @var float
     *
     * @ORM\Column(name="price_btc", type="float", nullable=true)
     */
    private $priceBtc;

    /**
     * @var float
     *
     * @ORM\Column(name="market_cap_usd", type="float", nullable=true)
     */
    private $marketCapUsd;

    /**
     * @var float
     *
     * @ORM\Column(name="available_supply", type="float", nullable=true)
     */
    private $availableSupply;

    /**
     * @var float
     *
     * @ORM\Column(name="total_supply", type="float", nullable=true)
     */
    private $totalSupply;

    /**
     * @var float
     *
     * @ORM\Column(name="percent_change_1h", type="float", nullable=true)
     */
    private $percentChange1h;

    /**
     * @var float
     *
     * @ORM\Column(name="percent_change_24h", type="float", nullable=true)
     */
    private $percentChange24h;

    /**
     * @var float
     *
     * @ORM\Column(name="percent_change_7d", type="float", nullable=true)
     */
    private $percentChange7d;

    /**
     * @var int
     * @ORM\Column(name="last_updated", type="integer", nullable=true)
     */
    private $lastUpdated;

    /**
     * @var float
     *
     * @ORM\Column(name="price_eur", type="float", nullable=true)
     */
    private $priceEur;

    /**
     * @var float
     *
     * @ORM\Column(name="market_cap_eur", type="float", nullable=true)
     */
    private $marketCapEur;

    /**
     * @var float
     *
     * @ORM\Column(name="volume_usd_24h", type="float", nullable=true)
     */
    private $volumeUsd24h;

    /**
     * @var float
     *
     * @ORM\Column(name="volume_eur_24h", type="float", nullable=true)
     */
    private $volumeEur24h;

    /**
     * @Assert\NotNull()
     * @ORM\OneToOne(targetEntity="Currency", inversedBy="currencyValueMoment")
     * @ORM\JoinColumn(name="currency_id", referencedColumnName="id", nullable=false)
     **/
    private $currency;

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set rank
     *
     * @param integer $rank
     *
     * @return CurrencyValueMoment
     */
    public function setRank($rank)
    {
        $this->rank = $rank;

        return $this;
    }

    /**
     * Get rank
     *
     * @return int
     */
    public function getRank()
    {
        return $this->rank;
    }

    /**
     * Set priceUsd
     *
     * @param float $priceUsd
     *
     * @return CurrencyValueMoment
     */
    public function setPriceUsd($priceUsd)
    {
        $this->priceUsd = $priceUsd;

        return $this;
    }

    /**
     * Get priceUsd
     *
     * @return float
     */
    public function getPriceUsd()
    {
        return $this->priceUsd;
    }

    /**
     * Set priceBtc
     *
     * @param float $priceBtc
     *
     * @return CurrencyValueMoment
     */
    public function setPriceBtc($priceBtc)
    {
        $this->priceBtc = $priceBtc;

        return $this;
    }

    /**
     * Get priceBtc
     *
     * @return float
     */
    public function getPriceBtc()
    {
        return $this->priceBtc;
    }

    /**
     * Set marketCapUsd
     *
     * @param float $marketCapUsd
     *
     * @return CurrencyValueMoment
     */
    public function setMarketCapUsd($marketCapUsd)
    {
        $this->marketCapUsd = $marketCapUsd;

        return $this;
    }

    /**
     * Get marketCapUsd
     *
     * @return float
     */
    public function getMarketCapUsd()
    {
        return $this->marketCapUsd;
    }

    /**
     * Set availableSupply
     *
     * @param float $availableSupply
     *
     * @return CurrencyValueMoment
     */
    public function setAvailableSupply($availableSupply)
    {
        $this->availableSupply = $availableSupply;

        return $this;
    }

    /**
     * Get availableSupply
     *
     * @return float
     */
    public function getAvailableSupply()
    {
        return $this->availableSupply;
    }

    /**
     * Set totalSupply
     *
     * @param float $totalSupply
     *
     * @return CurrencyValueMoment
     */
    public function setTotalSupply($totalSupply)
    {
        $this->totalSupply = $totalSupply;

        return $this;
    }

    /**
     * Get totalSupply
     *
     * @return float
     */
    public function getTotalSupply()
    {
        return $this->totalSupply;
    }

    /**
     * Set percentChange1h
     *
     * @param float $percentChange1h
     *
     * @return CurrencyValueMoment
     */
    public function setPercentChange1h($percentChange1h)
    {
        $this->percentChange1h = $percentChange1h;

        return $this;
    }

    /**
     * Get percentChange1h
     *
     * @return float
     */
    public function getPercentChange1h()
    {
        return $this->percentChange1h;
    }

    /**
     * Set percentChange24h
     *
     * @param float $percentChange24h
     *
     * @return CurrencyValueMoment
     */
    public function setPercentChange24h($percentChange24h)
    {
        $this->percentChange24h = $percentChange24h;

        return $this;
    }

    /**
     * Get percentChange24h
     *
     * @return float
     */
    public function getPercentChange24h()
    {
        return $this->percentChange24h;
    }

    /**
     * Set percentChange7d
     *
     * @param float $percentChange7d
     *
     * @return CurrencyValueMoment
     */
    public function setPercentChange7d($percentChange7d)
    {
        $this->percentChange7d = $percentChange7d;

        return $this;
    }

    /**
     * Get percentChange7d
     *
     * @return float
     */
    public function getPercentChange7d()
    {
        return $this->percentChange7d;
    }

    /**
     * Set lastUpdated
     *
     * @param integer $lastUpdated
     *
     * @return CurrencyValueMoment
     */
    public function setLastUpdated($lastUpdated)
    {
        $this->lastUpdated = $lastUpdated;

        return $this;
    }

    /**
     * Get lastUpdated
     *
     * @return int
     */
    public function getLastUpdated()
    {
        return $this->lastUpdated;
    }

    /**
     * Set priceEur
     *
     * @param float $priceEur
     *
     * @return CurrencyValueMoment
     */
    public function setPriceEur($priceEur)
    {
        $this->priceEur = $priceEur;

        return $this;
    }

    /**
     * Get priceEur
     *
     * @return float
     */
    public function getPriceEur()
    {
        return $this->priceEur;
    }

    /**
     * Set marketCapEur
     *
     * @param float $marketCapEur
     *
     * @return CurrencyValueMoment
     */
    public function setMarketCapEur($marketCapEur)
    {
        $this->marketCapEur = $marketCapEur;

        return $this;
    }

    /**
     * Get marketCapEur
     *
     * @return float
     */
    public function getMarketCapEur()
    {
        return $this->marketCapEur;
    }

    /**
     * Set volumeUsd24h
     *
     * @param float $volumeUsd24h
     *
     * @return CurrencyValueMoment
     */
    public function setVolumeUsd24h($volumeUsd24h)
    {
        $this->volumeUsd24h = $volumeUsd24h;

        return $this;
    }

    /**
     * Get volumeUsd24h
     *
     * @return float
     */
    public function getVolumeUsd24h()
    {
        return $this->volumeUsd24h;
    }

    /**
     * Set volumeEur24h
     *
     * @param float $volumeEur24h
     *
     * @return CurrencyValueMoment
     */
    public function setVolumeEur24h($volumeEur24h)
    {
        $this->volumeEur24h = $volumeEur24h;

        return $this;
    }

    /**
     * Get volumeEur24h
     *
     * @return float
     */
    public function getVolumeEur24h()
    {
        return $this->volumeEur24h;
    }

    /**
     * Set currency
     *
     * @param \App\Entity\Currency $currency
     *
     * @return CurrencyValueMoment
     */
    public function setCurrency(\App\Entity\Currency $currency = null)
    {
        $this->currency = $currency;

        return $this;
    }

    /**
     * Get currency
     *
     * @return \App\Entity\Currency
     */
    public function getCurrency()
    {
        return $this->currency;
    }
}
