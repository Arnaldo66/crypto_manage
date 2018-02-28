<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * MyCryptoWalletDetail
 *
 * @ORM\Table(name="my_crypto_wallet_detail")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\MyCryptoWalletDetailRepository")
 */
class MyCryptoWalletDetail
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
     * @ORM\Column(name="amount", type="float")
     */
    private $amount;

    /**
     * @Assert\NotNull()
     * @ORM\ManyToOne(targetEntity="MyCryptoWallet", inversedBy="details")
     * @ORM\JoinColumn(name="my_crypto_wallet_id", referencedColumnName="id", nullable=false)
     */
    private $myCryptoWallet;

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
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set amount
     *
     * @param float $amount
     *
     * @return MyCryptoWalletDetail
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Get amount
     *
     * @return float
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return MyCryptoWalletDetail
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
     * @return MyCryptoWalletDetail
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
     * Set myCryptoWallet
     *
     * @param \AppBundle\Entity\MyCryptoWallet $myCryptoWallet
     *
     * @return MyCryptoWalletDetail
     */
    public function setMyCryptoWallet(\AppBundle\Entity\MyCryptoWallet $myCryptoWallet)
    {
        $this->myCryptoWallet = $myCryptoWallet;

        return $this;
    }

    /**
     * Get myCryptoWallet
     *
     * @return \AppBundle\Entity\MyCryptoWallet
     */
    public function getMyCryptoWallet()
    {
        return $this->myCryptoWallet;
    }
}
