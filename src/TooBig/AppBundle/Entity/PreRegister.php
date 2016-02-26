<?php

namespace TooBig\AppBundle\Entity;
use Symfony\Component\Validator\Constraints\DateTime;

/**
 * PreRegister
 */
class PreRegister
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $phone;

    /**
     * @var int
     */
    private $sms;

    /**
     * @var float
     */
    private $cost;

    /**
     * @var bool
     */
    private $status;

    /**
     * @var int
     */
    private $code;

    /**
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @var \DateTime
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
     * Set phone
     *
     * @param string $phone
     *
     * @return PreRegister
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    
        return $this;
    }

    /**
     * Get phone
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set sms
     *
     * @param int $sms
     *
     * @return PreRegister
     */
    public function setSms($sms)
    {
        $this->sms = $sms;
    
        return $this;
    }

    /**
     * Get sms
     *
     * @return int
     */
    public function getSms()
    {
        return $this->sms;
    }

    /**
     * Set cost
     *
     * @param float $cost
     *
     * @return PreRegister
     */
    public function setCost($cost)
    {
        $this->cost = $cost;
    
        return $this;
    }

    /**
     * Get cost
     *
     * @return float
     */
    public function getCost()
    {
        return $this->cost;
    }

    /**
     * Set status
     *
     * @param bool $status
     *
     * @return PreRegister
     */
    public function setStatus($status)
    {
        $this->status = $status;
    
        return $this;
    }

    /**
     * Get status
     *
     * @return bool
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set code
     *
     * @param int $code
     *
     * @return PreRegister
     */
    public function setCode($code)
    {
        $this->code = $code;
    
        return $this;
    }

    /**
     * Get code
     *
     * @return int
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param \DateTime $updatedAt
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }
}

