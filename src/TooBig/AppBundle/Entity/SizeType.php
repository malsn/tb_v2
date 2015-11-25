<?php

namespace TooBig\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
/**
 * SizeType
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="TooBigAppBundle\Entity\SizeTypeRepository")
 */
class SizeType
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
     * @var Size[]
     */
    private $sizes;
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
     * @return SizeType
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
     * @return Size[]
     */
    public function getSizes()
    {
        return $this->sizes;
    }

    /**
     * @param Size[] $sizes
     */
    public function setSizes($sizes)
    {
        $this->sizes = $sizes;
    }



    public function __toString(){
        return $this->getName();
    }
}

