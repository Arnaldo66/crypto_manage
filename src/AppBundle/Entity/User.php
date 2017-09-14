<?php
// src/AppBundle/Entity/User.php

namespace AppBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="currency_user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\OneToMany(targetEntity="TradingWallet", mappedBy="user")
     */
    private $tradingWallets;

    public function __construct()
    {
      parent::__construct();
      $this->tradingWallets = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add tradingWallet
     *
     * @param \AppBundle\Entity\TradingWallet $tradingWallet
     *
     * @return User
     */
    public function addTradingWallet(\AppBundle\Entity\TradingWallet $tradingWallet)
    {
        $this->tradingWallets[] = $tradingWallet;

        return $this;
    }

    /**
     * Remove tradingWallet
     *
     * @param \AppBundle\Entity\TradingWallet $tradingWallet
     */
    public function removeTradingWallet(\AppBundle\Entity\TradingWallet $tradingWallet)
    {
        $this->tradingWallets->removeElement($tradingWallet);
    }

    /**
     * Get tradingWallets
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTradingWallets()
    {
        return $this->tradingWallets;
    }
}
