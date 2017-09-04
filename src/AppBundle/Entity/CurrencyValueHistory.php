<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * CurrencyValueHistory
 *
 * @ORM\Table(name="currency_value_history")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CurrencyValueHistoryRepository")
 */
class CurrencyValueHistory
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
     * @Assert\NotBlank()
     * @ORM\Column(name="hight_usd", type="float")
     */
    private $hightUsd;

    /**
     * @var float
     * @Assert\NotBlank()
     * @ORM\Column(name="low_usd", type="float")
     */
    private $lowUsd;

    /**
     * @var float
     * @Assert\NotBlank()
     * @ORM\Column(name="hight_eur", type="float")
     */
    private $hightEur;

    /**
     * @var float
     * @Assert\NotBlank()
     * @ORM\Column(name="low_eur", type="float")
     */
    private $lowEur;

    /**
     * @var float
     * @Assert\NotBlank()
     * @ORM\Column(name="average_usd", type="float")
     */
    private $averageUsd;

    /**
     * @var float
     * @Assert\NotBlank()
     * @ORM\Column(name="average_eur", type="float")
     */
    private $averageEur;

    /**
     * @var \DateTime
     * @Assert\NotBlank()
     * @ORM\Column(name="day", type="date")
     */
    private $day;

    /**
     * @Assert\NotNull()
     * @ORM\ManyToOne(targetEntity="Currency", inversedBy="currencyHistoryValues")
     * @ORM\JoinColumn(name="currency_id", referencedColumnName="id", nullable=false)
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
     * Set hightUsd
     *
     * @param float $hightUsd
     *
     * @return CurrencyValueHistory
     */
    public function setHightUsd($hightUsd)
    {
        $this->hightUsd = $hightUsd;

        return $this;
    }

    /**
     * Get hightUsd
     *
     * @return float
     */
    public function getHightUsd()
    {
        return $this->hightUsd;
    }

    /**
     * Set low
     *
     * @param float $lowUsd
     *
     * @return CurrencyValueHistory
     */
    public function setLowUsd($lowUsd)
    {
        $this->lowUsd = $lowUsd;

        return $this;
    }

    /**
     * Get lowUsd
     *
     * @return float
     */
    public function getLowUsd()
    {
        return $this->lowUsd;
    }

    /**
     * Set day
     *
     * @param \DateTime $day
     *
     * @return CurrencyValueHistory
     */
    public function setDay($day)
    {
        $this->day = $day;

        return $this;
    }

    /**
     * Get day
     *
     * @return \DateTime
     */
    public function getDay()
    {
        return $this->day;
    }

    /**
     * Set currency
     *
     * @param \AppBundle\Entity\Currency $currency
     *
     * @return CurrencyValueHistory
     */
    public function setCurrency(\AppBundle\Entity\Currency $currency)
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

    /**
     * Set averageUsd
     *
     * @param float $aaverageUsd
     *
     * @return CurrencyValueHistory
     */
    public function setAverageUsd($averageUsd)
    {
        $this->averageUsd = $averageUsd;

        return $this;
    }

    /**
     * Get averageUsd
     *
     * @return float
     */
    public function getAverageUsd()
    {
        return $this->averageUsd;
    }

    /**
     * Set hightEur
     *
     * @param float $hightEur
     *
     * @return CurrencyValueHistory
     */
    public function setHightEur($hightEur)
    {
        $this->hightEur = $hightEur;

        return $this;
    }

    /**
     * Get hightEur
     *
     * @return float
     */
    public function getHightEur()
    {
        return $this->hightEur;
    }

    /**
     * Set lowEur
     *
     * @param float $lowEur
     *
     * @return CurrencyValueHistory
     */
    public function setLowEur($lowEur)
    {
        $this->lowEur = $lowEur;

        return $this;
    }

    /**
     * Get lowEur
     *
     * @return float
     */
    public function getLowEur()
    {
        return $this->lowEur;
    }

    /**
     * Set averageEur
     *
     * @param float $averageEur
     *
     * @return CurrencyValueHistory
     */
    public function setAverageEur($averageEur)
    {
        $this->averageEur = $averageEur;

        return $this;
    }

    /**
     * Get averageEur
     *
     * @return float
     */
    public function getAverageEur()
    {
        return $this->averageEur;
    }
}
