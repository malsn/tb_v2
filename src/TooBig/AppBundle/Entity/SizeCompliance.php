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
    public function getSizeId1()
    {
        return $this->size_1;
    }

    /**
     * @param Size $size_1
     */
    public function setSizeId1($size_1)
    {
        $this->size_id_1 = $size_1;
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
     * @return Size
     */
    public function getSizeId2()
    {
        return $this->size_2;
    }

    /**
     * @param Size $size_2
     */
    public function setSizeId2($size_2)
    {
        $this->size_id_2 = $size_2;
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
}

