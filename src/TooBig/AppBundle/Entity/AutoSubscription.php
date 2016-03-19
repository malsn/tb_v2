<?php

namespace TooBig\AppBundle\Entity;
use Application\Iphp\CoreBundle\Entity\Rubric;
use Doctrine\Common\Collections\ArrayCollection;
use Sonata\UserBundle\Model\UserInterface;

/**
 * AutoSubscription
 */
class AutoSubscription
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $title;

    /**
     * @var bool
     */
    private $enabled;

    /**
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @var \DateTime
     */
    private $viewedAt;

    /**
     * @var \DateTime
     */
    private $modifiedAt;

    /**
     * @var int
     */
    private $priceMin;

    /**
     * @var int
     */
    private $priceMax;

    /**
     * @var string
     */
    private $gender;

    /**
     * @var Rubric
     */
    private $rubric;

    /**
     * @var UserInterface
     */
    private $createdBy;

    /**
     * @var Brand
     */
    private $brand;


    /**
     * @var Model
     */
    private $model;

    /**
     * @var ArrayCollection
     */

    private $color;

    /**
     * @var SizeType
     */
    private $size_type;

    /**
     * @var Size
     */
    private $size;


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
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return boolean
     */
    public function isEnabled()
    {
        return $this->enabled;
    }

    /**
     * @param boolean $enabled
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return AutoSubscription
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
     * @return \DateTime
     */
    public function getViewedAt()
    {
        return $this->viewedAt;
    }

    /**
     * @param \DateTime $viewedAt
     */
    public function setViewedAt($viewedAt)
    {
        $this->viewedAt = $viewedAt;
    }

    /**
     * Set modifiedAt
     *
     * @param \DateTime $modifiedAt
     *
     * @return AutoSubscription
     */
    public function setModifiedAt($modifiedAt)
    {
        $this->modifiedAt = $modifiedAt;
    
        return $this;
    }

    /**
     * Get modifiedAt
     *
     * @return \DateTime
     */
    public function getModifiedAt()
    {
        return $this->modifiedAt;
    }

    /**
     * Set priceMin
     *
     * @param int $priceMin
     *
     * @return AutoSubscription
     */
    public function setPriceMin($priceMin)
    {
        $this->priceMin = $priceMin;
    
        return $this;
    }

    /**
     * Get priceMin
     *
     * @return int
     */
    public function getPriceMin()
    {
        return $this->priceMin;
    }

    /**
     * Set priceMax
     *
     * @param int $priceMax
     *
     * @return AutoSubscription
     */
    public function setPriceMax($priceMax)
    {
        $this->priceMax = $priceMax;
    
        return $this;
    }

    /**
     * Get priceMax
     *
     * @return int
     */
    public function getPriceMax()
    {
        return $this->priceMax;
    }

    /**
     * Set gender
     *
     * @param string $gender
     *
     * @return AutoSubscription
     */
    public function setGender($gender)
    {
        $this->gender = $gender;
    
        return $this;
    }

    /**
     * Get gender
     *
     * @return string
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * @return Rubric
     */
    public function getRubric()
    {
        return $this->rubric;
    }

    /**
     * @return UserInterface
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    /**
     * @param UserInterface $createdBy
     */
    public function setCreatedBy($createdBy)
    {
        $this->createdBy = $createdBy;
    }

    /**
     * @param Rubric $rubric
     */
    public function setRubric($rubric)
    {
        $this->rubric = $rubric;
    }

    /**
     * @return Brand
     */
    public function getBrand()
    {
        return $this->brand;
    }

    /**
     * @param Brand $brand
     */
    public function setBrand($brand)
    {
        $this->brand = $brand;
    }

    /**
     * @return Model
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * @param Model $model
     */
    public function setModel($model)
    {
        $this->model = $model;
    }

    /**
     * @return ArrayCollection
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * @param ArrayCollection $color
     */
    public function setColor($color)
    {
        $this->color = $color;
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

    /**
     * @return Size
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * @param Size $size
     */
    public function setSize($size)
    {
        $this->size = $size;
    }

}

