<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CurrencyValueDay
 *
 * @ORM\Table(name="currency_value_day")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CurrencyValueDayRepository")
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
     * @ORM\Column(name="price_usd", type="float")
     */
    private $priceUsd;

    /**
     * @var float
     *
     * @ORM\Column(name="price_btc", type="float")
     */
    private $priceBtc;

    /**
     * @var float
     *
     * @ORM\Column(name="price_eur", type="float")
     */
    private $priceEur;

    /**
     * @ORM\ManyToOne(targetEntity="Currency", inversedBy="currencyValues")
     * @ORM\JoinColumn(name="currency_id", referencedColumnName="id")
     */
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
     * @param \AppBundle\Entity\Currency $currency
     *
     * @return CurrencyValueDay
     */
    public function setCurrency(\AppBundle\Entity\Currency $currency = null)
    {
        $this->currency = $currency;

        return $this;
    }

    /**
     * Get currency
     *
     * @return \AppBundle\Entity\Currency
     */
    public function getCurrency()
    {
        return $this->currency;
    }
}
