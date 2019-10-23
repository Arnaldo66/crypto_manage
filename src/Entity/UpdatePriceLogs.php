<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UpdatePriceLogs
 *
 * @ORM\Table(name="update_price_logs")
 * @ORM\Entity()
 */
class UpdatePriceLogs
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
     * @ORM\Column(name="is_active", type="boolean")
     */
    private $success;

    /**
     * @ORM\Column(name="message", type="string", nullable=true)
     */
    private $message;

    /**
     * @ORM\Column(name="created_at", type="date")
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

    public function setSuccess($success)
    {
        $this->success = $success;

        return $this;
    }

    public function getSuccess()
    {
        return $this->success;
    }

    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }
}
