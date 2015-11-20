<?php

namespace TooBig\AppBundle\Entity;

/**
 * Size
 */
class Size
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $value;

    /**
     * @var SizeType
     */
    private $size_type;

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
     * Set value
     *
     * @param string $value
     *
     * @return Size
     */
    public function setValue($value)
    {
        $this->value = $value;
    
        return $this;
    }

    /**
     * Get value
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return SizeType
     */
    public function getSizeType()
    {
        return $this->size_type;
    }

    /**
     * @param SizeType $size_type
     */
    public function setSizeType($size_type)
    {
        $this->size_type = $size_type;
    }

    public function __toString(){
        return $this->getValue();
    }
}

