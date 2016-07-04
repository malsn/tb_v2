<?php

namespace TooBig\AppBundle\Entity;

/**
 * SizeCompliance
 */
class SizeCompliance
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @var \DateTime
     */
    private $updatedAt;
    /**
     * @var Size
     */
    private $size_1;

    /**
     * @var SizeType
     */
    private $size_type_1;

    /**
     * @var SizeCountry
     */
    private $size_country_1;

    /**
     * @var Size
     */
    private $size_2;

    /**
     * @var SizeType
     */
    private $size_type_2;

    /**
     * @var SizeCountry
     */
    private $size_country_2;

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
     * @return Size
     */
    public function getSize1()
    {
        return $this->size_1;
    }

    /**
     * @param Size $size_1
     */
    public function setSize1($size_1)
    {
        $this->size_1 = $size_1;
    }

    /**
     * @return Size
     */
    public function getSize2()
    {
        return $this->size_2;
    }

    /**
     * @param Size $size_2
     */
    public function setSize2($size_2)
    {
        $this->size_2 = $size_2;
    }

    /**
     * @return SizeType
     */
    public function getSizeType1()
    {
        return $this->size_type_1;
    }

    /**
     * @param SizeType $size_type_1
     */
    public function setSizeType1($size_type_1)
    {
        $this->size_type_1 = $size_type_1;
    }

    /**
     * @return SizeCountry
     */
    public function getSizeCountry1()
    {
        return $this->size_country_1;
    }

    /**
     * @param SizeCountry $size_country_1
     */
    public function setSizeCountry1($size_country_1)
    {
        $this->size_country_1 = $size_country_1;
    }

    /**
     * @return SizeType
     */
    public function getSizeType2()
    {
        return $this->size_type_2;
    }

    /**
     * @param SizeType $size_type_2
     */
    public function setSizeType2($size_type_2)
    {
        $this->size_type_2 = $size_type_2;
    }

    /**
     * @return SizeCountry
     */
    public function getSizeCountry2()
    {
        return $this->size_country_2;
    }

    /**
     * @param SizeCountry $size_country_2
     */
    public function setSizeCountry2($size_country_2)
    {
        $this->size_country_2 = $size_country_2;
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
}

