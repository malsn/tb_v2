<?php

namespace TooBig\AppBundle\Entity;

/**
 * Model
 */
class Model
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $name;


    /**
     * @var Brand
     */
    private $brand_id;


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
     * @return Model
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
     * @return Brand
     */
    public function getBrandId()
    {
        return $this->brand_id;
    }

    /**
     * @param Brand $brand_id
     */
    public function setBrandId($brand_id)
    {
        $this->brand_id = $brand_id;
    }


}

