<?php

namespace TooBig\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Iphp\FileStoreBundle\Mapping\Annotation as FileStore;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * Brand
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="TooBigAppBundle\Entity\BrandRepository")
 */
class Brand
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
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var text
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @Assert\Image(
     *     maxSize="20M"
     * )
     * @FileStore\UploadableField(mapping="brand_image", fileDataProperty ="image"))
     *
     * @var File $image
     */
    protected $imageUpload;

    /**
     * @var array
     * @ORM\Column(name="image", type="array", nullable=true)
     */
    protected $image;

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
     * @return Brand
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
     * @return text
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param text $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Brand
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
     * @return mixed
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param mixed $image
     */
    public function setImage($image)
    {
        $this->image = $image;
    }

    /**
     * @param \Iphp\ContentBundle\Model\File $imageUpload
     * @return $this
     */
    public function setImageUpload($imageUpload)
    {
        $this->imageUpload = $imageUpload;
        return $this;
    }

    /**
     * @return \Iphp\ContentBundle\Model\File
     */
    public function getImageUpload()
    {
        return $this->imageUpload;
    }


    public function __toString(){
        return $this->getName();
    }

    public function prePersist()
    {
        if (!$this->getCreatedAt()) $this->setCreatedAt(new \DateTime);
    }
}

