<?php
// src/AppBundle/Entity/User.php

namespace AppBundle\Entity;

use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity
 * @ORM\Table(name="currency_user")
 * @UniqueEntity("email")
 * @UniqueEntity("username")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 */
class User implements UserInterface, \Serializable
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Assert\Length(max=255)
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;


    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $tokenResetPassword;

    /**
     * @ORM\Column(name="token_reset_password_send_at", type="datetime", nullable=true)
     */
    private $tokenResetPasswordSentAt;

    /**
     * @Assert\NotBlank
     * @Assert\Length(max=4096)
     */
    private $plainPassword;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Assert\Email
     */
    private $email;

    /**
     * @ORM\Column(name="is_active", type="boolean")
     */
    private $isActive;


    /**
     * @ORM\OneToMany(targetEntity="TradingWallet", mappedBy="user")
     */
    private $tradingWallets;

    /**
     * @ORM\OneToMany(targetEntity="Alert", mappedBy="user")
     */
    private $alerts;

    /**
     * @ORM\OneToMany(targetEntity="MyCryptoWallet", mappedBy="user")
     */
    private $myCryptoWallets;

    /**
     * @ORM\Column(type="array")
     */
    private $roles;

    public function __construct()
    {
        $this->isActive = true;
        $this->tradingWallets = new \Doctrine\Common\Collections\ArrayCollection();
        $this->alerts = new \Doctrine\Common\Collections\ArrayCollection();
        $this->myCryptoWallets = new \Doctrine\Common\Collections\ArrayCollection();
        $this->roles = [];
    }

    public function getId()
    {
        return $this->id;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function setUsername($username)
    {
        $this->username = $username;
    }

    public function getSalt()
    {
        // you *may* need a real salt depending on your encoder
        // see section on salt below
        return null;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    public function setPlainPassword($password)
    {
        $this->plainPassword = $password;
    }

    public function getRoles()
    {
        return $this->roles;
    }


    public function getTokenResetPassword()
    {
        return $this->tokenResetPassword;
    }

    public function setTokenResetPassword($tokenResetPassword)
    {
        $this->tokenResetPassword = $tokenResetPassword;
    }

    public function getTokenResetPasswordSentAt()
    {
        return $this->tokenResetPasswordSentAt;
    }

    public function setTokenResetPasswordSentAt($tokenResetPasswordSentAt)
    {
        $this->tokenResetPasswordSentAt = $tokenResetPasswordSentAt;
    }


    public function eraseCredentials()
    {
    }

    public function serialize()
    {
        return serialize([
            $this->id,
            $this->username,
            $this->password,
        ]);
    }

    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->username,
            $this->password,
        ) = unserialize($serialized, ['allowed_classes' => false]);
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

    /**
     * Add alert
     *
     * @param \AppBundle\Entity\Alert $alert
     *
     * @return User
     */
    public function addAlert(\AppBundle\Entity\Alert $alert)
    {
        $this->alerts[] = $alert;

        return $this;
    }

    /**
     * Remove alert
     *
     * @param \AppBundle\Entity\Alert $alert
     */
    public function removeAlert(\AppBundle\Entity\Alert $alert)
    {
        $this->alerts->removeElement($alert);
    }

    /**
     * Get alerts
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAlerts()
    {
        return $this->alerts;
    }

    /**
     * Add myCryptoWallet
     *
     * @param \AppBundle\Entity\MyCryptoWallet $myCryptoWallet
     *
     * @return User
     */
    public function addMyCryptoWallet(\AppBundle\Entity\MyCryptoWallet $myCryptoWallet)
    {
        $this->myCryptoWallets[] = $myCryptoWallet;

        return $this;
    }

    /**
     * Remove myCryptoWallet
     *
     * @param \AppBundle\Entity\MyCryptoWallet $myCryptoWallet
     */
    public function removeMyCryptoWallet(\AppBundle\Entity\MyCryptoWallet $myCryptoWallet)
    {
        $this->myCryptoWallets->removeElement($myCryptoWallet);
    }

    /**
     * Get myCryptoWallets
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMyCryptoWallets()
    {
        return $this->myCryptoWallets;
    }

    public function __toString()
    {
        return $this->getUsername();
    }
}
