<?php

namespace CatMS\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="CatMS\AdminBundle\Repository\ContentGroupRepository")
 * @ORM\HasLifecycleCallbacks
 * @UniqueEntity("slug")
 * @ORM\Table(name="content_group")
 */
class ContentGroup
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
     *      min="4",
     *      max="255",
     *      minMessage="Slug must have at least {{ limit }} characters.",
     *      maxMessage="Slug must have at least {{ limit }} characters."
     * )
     * @var string
     */
    private $slug;
    
    /**
     * @ORM\Column(type="string", length=510, nullable=true)
     * @Assert\Length(
     *      max="510",
     *      maxMessage="Content group description must have no more than {{ limit }} characters."
     * ) 
     */
    private $description;
    
    /**
     * @ORM\Column(type="text", nullable=true)
     * @var string 
     */
    private $manual;
    
    /**
     * @ORM\Column(type="text", length=3, nullable=false)
     * @Assert\Choice(choices = {"777", "755", "000"}, message = "Choose a valid access value.")
     * @var integer 
     */
    private $isRemovable = "777";
    
    /**
     * @ORM\OneToMany(targetEntity="CatMS\AdminBundle\Entity\ContentManager", mappedBy="contentGroup", cascade={"persist", "remove"})
     * @var object \CatMS\AdminBundle\Entity\ContentManager
     */
    private $contents;
    
    /**
     * @ORM\ManyToMany(targetEntity="CatMS\AdminBundle\Entity\ImageGroup", inversedBy="relatedContents")
     * @ORM\JoinTable(name="contents_images")
     */
    private $relatedImages;
    
    /**
     * @ORM\OneToOne(targetEntity="CatMS\AdminBundle\Entity\ContentFields", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="content_fields_id", referencedColumnName="id")
     */
    private $contentFields;
    
    public function __construct() {
        $this->contents = new \Doctrine\Common\Collections\ArrayCollection(); 
        $this->relatedImages = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    public function __toString() {
        return $this->description;
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
     * @return ContentGroup
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
     * @return ContentGroup
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
     * Add contents
     *
     * @param \CatMS\AdminBundle\Entity\ContentManager $contents
     * @return ContentGroup
     */
    public function addContent(\CatMS\AdminBundle\Entity\ContentManager $contents)
    {
        $this->contents[] = $contents;

        return $this;
    }

    /**
     * Remove contents
     *
     * @param \CatMS\AdminBundle\Entity\ContentManager $contents
     */
    public function removeContent(\CatMS\AdminBundle\Entity\ContentManager $contents)
    {
        $this->contents->removeElement($contents);
    }

    /**
     * Get contents
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getContents()
    {
        return $this->contents;
    }

    /**
     * Set manual
     *
     * @param string $manual
     * @return ContentGroup
     */
    public function setManual($manual)
    {
        $this->manual = $manual;

        return $this;
    }

    /**
     * Get manual
     *
     * @return string 
     */
    public function getManual()
    {
        return $this->manual;
    }

    /**
     * Add relatedImages
     *
     * @param \CatMS\AdminBundle\Entity\ImageGroup $relatedImages
     * @return ContentGroup
     */
    public function addRelatedImage(\CatMS\AdminBundle\Entity\ImageGroup $relatedImages)
    {
        $this->relatedImages[] = $relatedImages;

        return $this;
    }

    /**
     * Remove relatedImages
     *
     * @param \CatMS\AdminBundle\Entity\ImageGroup $relatedImages
     */
    public function removeRelatedImage(\CatMS\AdminBundle\Entity\ImageGroup $relatedImages)
    {
        $this->relatedImages->removeElement($relatedImages);
    }

    /**
     * Get relatedImages
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getRelatedImages()
    {
        return $this->relatedImages;
    }

    /**
     * Set isRemovable
     *
     * @param integer $isRemovable
     * @return ContentGroup
     */
    public function setIsRemovable($isRemovable)
    {
        $this->isRemovable = $isRemovable;

        return $this;
    }

    /**
     * Get isRemovable
     *
     * @return integer 
     */
    public function getIsRemovable()
    {
        return $this->isRemovable;
    }


    /**
     * Set contentFields
     *
     * @param \CatMS\AdminBundle\Entity\ContentFields $contentFields
     * @return ContentGroup
     */
    public function setContentFields(\CatMS\AdminBundle\Entity\ContentFields $contentFields = null)
    {
        $this->contentFields = $contentFields;

        return $this;
    }

    /**
     * Get contentFields
     *
     * @return \CatMS\AdminBundle\Entity\ContentFields 
     */
    public function getContentFields()
    {
        return $this->contentFields;
    }
}
