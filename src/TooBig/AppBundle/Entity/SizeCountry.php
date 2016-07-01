<?php

namespace TooBig\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
/**
 * SizeCountry
 * @ORM\Table()
 */
class SizeCountry
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
     * @return SizeCountry
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

    public function __toString(){
        return $this->getName();
    }
}

