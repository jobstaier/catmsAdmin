<?php

namespace CatMS\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="CatMS\AdminBundle\Repository\ImageGroupRepository")
 * @ORM\HasLifecycleCallbacks
 * @UniqueEntity("slug")
 * @ORM\Table(name="asset_group")
 */
class ImageGroup
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min="2",
     *      max="255",
     *      minMessage="Slug must have at least {{ limit }} characters.",
     *      maxMessage="Slug must have no more than {{ limit }} characters."
     * ) 
     * @var string
     */
    private $slug;
    
    /**
     * @ORM\Column(type="string", length=510, nullable=false)
     * @Assert\NotBlank()
     * @Assert\Length(
     *      max="510",
     *      maxMessage="Image group description must have no more than {{ limit }} characters."
     * ) 
     */
    private $description;
    
    /**
     *  @ORM\Column(type="integer", nullable=true)
     */
    private $imageWidth = null;
    
    /**
     *  @ORM\Column(type="integer", nullable=true)
     */
    private $imageHeight = null;
    
    /**
     * @ORM\OneToMany(targetEntity="CatMS\AdminBundle\Entity\ImageUpload", mappedBy="imageGroup")
     * @var object \CatMS\AdminBundle\Entity\ImageUpload
     */
    private $images;
    
    /**
     * @ORM\ManyToMany(targetEntity="CatMS\AdminBundle\Entity\ContentGroup", mappedBy="relatedImages", cascade={"persist"})
     */
    private $relatedContents;
    
    /**
     *  @ORM\Column(type="boolean")
     */
    protected $hasThumbnails = false;
    
    /**
     *  @ORM\Column(type="integer", nullable=true)
     */
    protected $thumbnailWidth;
    
    /**
     *  @ORM\Column(type="integer", nullable=true)
     */
    protected $thumbnailHeight;    
    
    public function __construct() {
        $this->images = new \Doctrine\Common\Collections\ArrayCollection();
        $this->relatedContents = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set slug
     *
     * @param string $slug
     * @return ImageGroup
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string 
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return ImageGroup
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set imageWidth
     *
     * @param integer $imageWidth
     * @return ImageGroup
     */
    public function setImageWidth($imageWidth)
    {
        $this->imageWidth = $imageWidth;

        return $this;
    }

    /**
     * Get imageWidth
     *
     * @return integer 
     */
    public function getImageWidth()
    {
        return $this->imageWidth;
    }

    /**
     * Set imageHeight
     *
     * @param integer $imageHeight
     * @return ImageGroup
     */
    public function setImageHeight($imageHeight)
    {
        $this->imageHeight = $imageHeight;

        return $this;
    }

    /**
     * Get imageHeight
     *
     * @return integer 
     */
    public function getImageHeight()
    {
        return $this->imageHeight;
    }

    /**
     * Add images
     *
     * @param \CatMS\AdminBundle\Entity\ImageUpload $images
     * @return ImageGroup
     */
    public function addImage(\CatMS\AdminBundle\Entity\ImageUpload $images)
    {
        $this->images[] = $images;

        return $this;
    }

    /**
     * Remove images
     *
     * @param \CatMS\AdminBundle\Entity\ImageUpload $images
     */
    public function removeImage(\CatMS\AdminBundle\Entity\ImageUpload $images)
    {
        $this->images->removeElement($images);
    }

    /**
     * Get images
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getImages()
    {
        return $this->images;
    }

    /**
     * Add relatedContents
     *
     * @param \CatMS\AdminBundle\Entity\ContentGroup $relatedContents
     * @return ImageGroup
     */
    public function addRelatedContent(\CatMS\AdminBundle\Entity\ContentGroup $relatedContents)
    {
        $this->relatedContents[] = $relatedContents;

        return $this;
    }

    /**
     * Remove relatedContents
     *
     * @param \CatMS\AdminBundle\Entity\ContentGroup $relatedContents
     */
    public function removeRelatedContent(\CatMS\AdminBundle\Entity\ContentGroup $relatedContents)
    {
        $this->relatedContents->removeElement($relatedContents);
    }

    /**
     * Get relatedContents
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getRelatedContents()
    {
        return $this->relatedContents;
    }
    
    /**
     * Set hasThumbnails
     *
     * @param boolean $hasThumbnails
     * @return ImageGroup
     */
    public function setHasThumbnails($hasThumbnails)
    {
        $this->hasThumbnails = $hasThumbnails;

        return $this;
    }

    /**
     * Get hasThumbnails
     *
     * @return boolean 
     */
    public function getHasThumbnails()
    {
        return $this->hasThumbnails;
    }

    /**
     * Set thumbnailWidth
     *
     * @param integer $thumbnailWidth
     * @return ImageGroup
     */
    public function setThumbnailWidth($thumbnailWidth)
    {
        $this->thumbnailWidth = $thumbnailWidth;

        return $this;
    }

    /**
     * Get thumbnailWidth
     *
     * @return integer 
     */
    public function getThumbnailWidth()
    {
        return $this->thumbnailWidth;
    }

    /**
     * Set thumbnailHeight
     *
     * @param integer $thumbnailHeight
     * @return ImageGroup
     */
    public function setThumbnailHeight($thumbnailHeight)
    {
        $this->thumbnailHeight = $thumbnailHeight;

        return $this;
    }

    /**
     * Get thumbnailHeight
     *
     * @return integer 
     */
    public function getThumbnailHeight()
    {
        return $this->thumbnailHeight;
    }
}
