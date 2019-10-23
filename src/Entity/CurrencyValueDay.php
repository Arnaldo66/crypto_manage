<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * CurrencyValueDay
 *
 * @ORM\Table(name="currency_value_day")
 * @ORM\Entity(repositoryClass="App\Repository\CurrencyValueDayRepository")
 */
class CurrencyValueDay
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
     * @ORM\Column(name="price_eur", type="float", nullable=true)
     */
    private $priceEur;

    /**
     * @Assert\NotNull()
     * @ORM\ManyToOne(targetEntity="Currency", inversedBy="currencyValues")
     * @ORM\JoinColumn(name="currency_id", referencedColumnName="id", nullable=false)
     */
    private $currency;

    /**
     * @var float
     *
     * @ORM\Column(name="volume_usd_24h", type="float", nullable=true)
     */
    private $volumeUsd24h;

    /**
     * @var datetime
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

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
     * Set priceUsd
     *
     * @param float $priceUsd
     *
     * @return CurrencyValueDay
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
     * @return CurrencyValueDay
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
     * Set priceEur
     *
     * @param float $priceEur
     *
     * @return CurrencyValueDay
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
     * Set currency
     *
     * @param \App\Entity\Currency $currency
     *
     * @return CurrencyValueDay
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

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return CurrencyValueDay
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
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
}
