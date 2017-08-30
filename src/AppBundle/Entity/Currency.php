<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Currency
 *
 * @ORM\Table(name="currency")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CurrencyRepository")
 * @UniqueEntity("name")
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
     * @ORM\Column(name="name", type="string", length=255, unique=true)
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
     * @var datetime
     * @Assert\NotNull()
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @var datetime
     * @Assert\NotNull()
     * @Gedmo\Timestampable(on="create")
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="updated_at", type="datetime")
     */
    private $updatedAt;

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
     * @param \AppBundle\Entity\CurrencyValueMoment $currencyValueMoment
     *
     * @return Currency
     */
    public function setCurrencyValueMoment(\AppBundle\Entity\CurrencyValueMoment $currencyValueMoment = null)
    {
        $this->currencyValueMoment = $currencyValueMoment;

        return $this;
    }

    /**
     * Get currencyValueMoment
     *
     * @return \AppBundle\Entity\CurrencyValueMoment
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
     * @param \AppBundle\Entity\CurrencyValueDay $currencyValue
     *
     * @return Currency
     */
    public function addCurrencyValue(\AppBundle\Entity\CurrencyValueDay $currencyValue)
    {
        $this->currencyValues[] = $currencyValue;

        return $this;
    }

    /**
     * Remove currencyValue
     *
     * @param \AppBundle\Entity\CurrencyValueDay $currencyValue
     */
    public function removeCurrencyValue(\AppBundle\Entity\CurrencyValueDay $currencyValue)
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
     * @param \AppBundle\Entity\CurrencyValueHistory $currencyHistoryValue
     *
     * @return Currency
     */
    public function addCurrencyHistoryValue(\AppBundle\Entity\CurrencyValueHistory $currencyHistoryValue)
    {
        $this->currencyHistoryValues[] = $currencyHistoryValue;

        return $this;
    }

    /**
     * Remove currencyHistoryValue
     *
     * @param \AppBundle\Entity\CurrencyValueHistory $currencyHistoryValue
     */
    public function removeCurrencyHistoryValue(\AppBundle\Entity\CurrencyValueHistory $currencyHistoryValue)
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
}
