<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Alert
 *
 * @ORM\Table(name="alert")
 * @ORM\Entity(repositoryClass="App\Repository\AlertRepository")
 */
class Alert
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
     * @ORM\Column(name="price", type="float")
     */
    private $price;

    /**
     * @var bool
     * @Assert\NotNull()
     * @ORM\Column(name="buy", type="boolean")
     */
    private $buy;

    /**
     * @Assert\NotNull()
     * @ORM\ManyToOne(targetEntity="Currency")
     * @ORM\JoinColumn(name="currency_id", referencedColumnName="id", nullable=false)
     */
    private $currency;

    /**
     * @Assert\NotNull()
     * @ORM\ManyToOne(targetEntity="User", inversedBy="alerts")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
     */
    private $user;

    /**
     * @var bool
     * @ORM\Column(name="can_delete", type="boolean", nullable=true)
     */
    private $canDelete;

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
     * Set price
     *
     * @param float $price
     *
     * @return Alert
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set buy
     *
     * @param boolean $buy
     *
     * @return Alert
     */
    public function setBuy($buy)
    {
        $this->buy = $buy;

        return $this;
    }

    /**
     * Get buy
     *
     * @return bool
     */
    public function getBuy()
    {
        return $this->buy;
    }

    /**
     * Set currency
     *
     * @param \App\Entity\Currency $currency
     *
     * @return Alert
     */
    public function setCurrency(\App\Entity\Currency $currency)
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
     * Set user
     *
     * @param \App\Entity\User $user
     *
     * @return Alert
     */
    public function setUser(\App\Entity\User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \App\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set canDelete
     *
     * @param boolean $canDelete
     *
     * @return Alert
     */
    public function setCanDelete($canDelete)
    {
        $this->canDelete = $canDelete;

        return $this;
    }

    /**
     * Get canDelete
     *
     * @return boolean
     */
    public function getCanDelete()
    {
        return $this->canDelete;
    }
}
