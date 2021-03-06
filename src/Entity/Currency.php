<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Currency
 *
 * @ORM\Table(name="currency")
 * @ORM\Entity(repositoryClass="App\Repository\CurrencyRepository")
 * @UniqueEntity("uniqueName")
 */
class Currency
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
     * @var string
     * @Assert\NotBlank()
     * @Assert\Length(max = 255)
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @Gedmo\Slug(fields={"name"})
     * @ORM\Column(length=128, unique=true)
     */
    private $slug;


    /**
     * @var string
     * @Assert\Length(max = 30)
     * @ORM\Column(name="symbol", type="string", length=30, nullable=true)
     */
    private $symbol;

    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Length(max = 255)
     * @ORM\Column(name="unique_name", type="string", length=255, unique=true)
     */
    private $uniqueName;


    /**
     * @var float
     *
     * @ORM\Column(name="price_usd", type="float", nullable=true)
     */
    private $priceUsd;

    /**
     * @var float
     *
     * @ORM\Column(name="price_eur", type="float", nullable=true)
     */
    private $priceEur;

    /**
     * @var float
     *
     * @ORM\Column(name="price_btc", type="float", nullable=true)
     */
    private $priceBtc;

    /**
     * @var int
     *
     * @Assert\NotBlank()
     * @ORM\Column(name="rank", type="integer")
     */
    private $rank;

    /**
     * @var datetime
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @var datetime
     * @Gedmo\Timestampable(on="create")
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="updated_at", type="datetime")
     */
    private $updatedAt;

    /**
     * @var string
     * @Assert\Length(max = 255)
     * @ORM\Column(name="image", type="string", length=255, nullable=true)
     */
    private $image;

    /**
     * @ORM\OneToOne(targetEntity="CurrencyValueMoment", mappedBy="currency")
     */
    private $currencyValueMoment;

    /**
     * @ORM\OneToMany(targetEntity="CurrencyValueDay", mappedBy="currency")
     */
    private $currencyValues;

    /**
     * @ORM\OneToMany(targetEntity="CurrencyValueHistory", mappedBy="currency")
     */
    private $currencyHistoryValues;


    /**
     * @var boolean
     * @ORM\Column(name="history", type="boolean", nullable=true)
     */
    private $history;


    /**
     * @var datetime
     * @ORM\Column(name="graph_year_date", type="datetime", nullable=true)
     */
    private $graphYearDate;

    /**
     * @ORM\Column(name="graph_year_data", type="text", nullable=true)
     */
    private $graphYearData;

    /**
     * @var string
     * @Assert\Length(max = 50)
     * @ORM\Column(name="graph_code", type="string", length=50, nullable=true)
     */
    private $graphCode;


    /**
     * @var int
     *
     * @ORM\Column(name="max_supply", type="bigint", nullable=true)
     */
    private $maxSupply;

     /**
      * @var int
      *
      * @ORM\Column(name="circulating_supply", type="bigint", nullable=true)
      */
    private $circulatingSupply;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->currencyValues = new \Doctrine\Common\Collections\ArrayCollection();
        $this->currencyHistoryValues = new \Doctrine\Common\Collections\ArrayCollection();
    }

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
     * Set name
     *
     * @param string $name
     *
     * @return Currency
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set symbol
     *
     * @param string $symbol
     *
     * @return Currency
     */
    public function setSymbol($symbol)
    {
        $this->symbol = $symbol;

        return $this;
    }

    /**
     * Get symbol
     *
     * @return string
     */
    public function getSymbol()
    {
        return $this->symbol;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Currency
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
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return Currency
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set currencyValueMoment
     *
     * @param \App\Entity\CurrencyValueMoment $currencyValueMoment
     *
     * @return Currency
     */
    public function setCurrencyValueMoment(\App\Entity\CurrencyValueMoment $currencyValueMoment = null)
    {
        $this->currencyValueMoment = $currencyValueMoment;

        return $this;
    }

    /**
     * Get currencyValueMoment
     *
     * @return \App\Entity\CurrencyValueMoment
     */
    public function getCurrencyValueMoment()
    {
        return $this->currencyValueMoment;
    }

    /**
     * Set slug
     *
     * @param string $slug
     *
     * @return Currency
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Add currencyValue
     *
     * @param \App\Entity\CurrencyValueDay $currencyValue
     *
     * @return Currency
     */
    public function addCurrencyValue(\App\Entity\CurrencyValueDay $currencyValue)
    {
        $this->currencyValues[] = $currencyValue;

        return $this;
    }

    /**
     * Remove currencyValue
     *
     * @param \App\Entity\CurrencyValueDay $currencyValue
     */
    public function removeCurrencyValue(\App\Entity\CurrencyValueDay $currencyValue)
    {
        $this->currencyValues->removeElement($currencyValue);
    }

    /**
     * Get currencyValues
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCurrencyValues()
    {
        return $this->currencyValues;
    }

    /**
     * Add currencyHistoryValue
     *
     * @param \App\Entity\CurrencyValueHistory $currencyHistoryValue
     *
     * @return Currency
     */
    public function addCurrencyHistoryValue(\App\Entity\CurrencyValueHistory $currencyHistoryValue)
    {
        $this->currencyHistoryValues[] = $currencyHistoryValue;

        return $this;
    }

    /**
     * Remove currencyHistoryValue
     *
     * @param \App\Entity\CurrencyValueHistory $currencyHistoryValue
     */
    public function removeCurrencyHistoryValue(\App\Entity\CurrencyValueHistory $currencyHistoryValue)
    {
        $this->currencyHistoryValues->removeElement($currencyHistoryValue);
    }

    /**
     * Get currencyHistoryValues
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCurrencyHistoryValues()
    {
        return $this->currencyHistoryValues;
    }

    /**
     * Set image
     *
     * @param string $image
     *
     * @return Currency
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set uniqueName
     *
     * @param string $uniqueName
     *
     * @return Currency
     */
    public function setUniqueName($uniqueName)
    {
        $this->uniqueName = $uniqueName;

        return $this;
    }

    /**
     * Get uniqueName
     *
     * @return string
     */
    public function getUniqueName()
    {
        return $this->uniqueName;
    }

    /**
     * Set priceUsd
     *
     * @param float $priceUsd
     *
     * @return Currency
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
     * Set priceEur
     *
     * @param float $priceEur
     *
     * @return Currency
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
     * Set rank
     *
     * @param integer $rank
     *
     * @return Currency
     */
    public function setRank($rank)
    {
        $this->rank = $rank;

        return $this;
    }

    /**
     * Get rank
     *
     * @return integer
     */
    public function getRank()
    {
        return $this->rank;
    }

    /**
     * show currency name and value for converter
     */
     public function getNameValue()
     {
         return $this->getName().': '.round($this->getPriceEur(),3);
     }

    /**
     * Set history
     *
     * @param boolean $history
     *
     * @return Currency
     */
    public function setHistory($history)
    {
        $this->history = $history;

        return $this;
    }

    /**
     * Get history
     *
     * @return boolean
     */
    public function getHistory()
    {
        return $this->history;
    }

    /**
     * Set graphYearDate
     *
     * @param \DateTime $graphYearDate
     *
     * @return Currency
     */
    public function setGraphYearDate($graphYearDate)
    {
        $this->graphYearDate = $graphYearDate;

        return $this;
    }

    /**
     * Get graphYearDate
     *
     * @return \DateTime
     */
    public function getGraphYearDate()
    {
        return $this->graphYearDate;
    }

    /**
     * Set graphYearData
     *
     * @param string $graphYearData
     *
     * @return Currency
     */
    public function setGraphYearData($graphYearData)
    {
        $this->graphYearData = $graphYearData;

        return $this;
    }

    /**
     * Get graphYearData
     *
     * @return string
     */
    public function getGraphYearData()
    {
        return $this->graphYearData;
    }

    /**
     * Set priceBtc
     *
     * @param float $priceBtc
     *
     * @return Currency
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
     * Set graphCode
     *
     * @param string $graphCode
     *
     * @return Currency
     */
    public function setGraphCode($graphCode)
    {
        $this->graphCode = $graphCode;

        return $this;
    }

    /**
     * Get graphCode
     *
     * @return string
     */
    public function getGraphCode()
    {
        return $this->graphCode;
    }

    /**
     * Set maxSupply
     *
     * @param integer $maxSupply
     *
     * @return Currency
     */
    public function setMaxSupply($maxSupply)
    {
        $this->maxSupply = $maxSupply;

        return $this;
    }

    /**
     * Get maxSupply
     *
     * @return integer
     */
    public function getMaxSupply()
    {
        return $this->maxSupply;
    }

    /**
     * Set circulatingSupply
     *
     * @param integer $circulatingSupply
     *
     * @return Currency
     */
    public function setCirculatingSupply($circulatingSupply)
    {
        $this->circulatingSupply = $circulatingSupply;

        return $this;
    }

    /**
     * Get circulatingSupply
     *
     * @return integer
     */
    public function getCirculatingSupply()
    {
        return $this->circulatingSupply;
    }
}
