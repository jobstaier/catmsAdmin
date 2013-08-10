<?php
namespace CatMS\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\True;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Table(name="content_manager")
 * @ORM\Entity(repositoryClass="CatMS\AdminBundle\Repository\ContentManagerRepository")
 * @UniqueEntity("slug")
 * @ORM\Table(name="content_manager")
 */
class ContentManager
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @var integer
     */
    private $id;
    
    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min="4",
     *      max="255",
     *      minMessage="Content slug must have at least {{ limit }} characters.",
     *      maxMessage="Content slug must have no more than {{ limit }} characters."
     * ) 
     * @var string
     */
    private $slug;
    
    /**
     * @ORM\Column(type="string", length=510, unique=false, nullable=false)
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min="3",
     *      max="510",
     *      minMessage="Description must have at least {{ limit }} characters.",
     *      maxMessage="Description must have no more than {{ limit }} characters."
     * ) 
     * @var string
     */
    private $description;
    
    /**
     * @ORM\Column(type="string", length=510, unique=false, nullable=true)
     * @Assert\Length(
     *      min="2",
     *      max="510",
     *      minMessage="Title must have at least {{ limit }} characters.",
     *      maxMessage="Title must have no more than {{ limit }} characters."
     * ) 
     * @var string
     */
    private $title;
    
    /**
     * @ORM\Column(type="string", length=1020, unique=false, name="short_text", nullable=true)
     * @Assert\Length(
     *      min="1",
     *      max="1020",
     *      minMessage="Short text must have at least {{ limit }} characters.",
     *      maxMessage="Short text must have no more than {{ limit }} characters."
     * ) 
     * @var string
     */
    private $shortText;
    
    /**
     * @ORM\Column(type="text", name="full_text",  unique=false)
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min="1",
     *      minMessage="Short text must have at least {{ limit }} characters."
     * ) 
     * @var string
     */
    private $fullText;
    
    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime", name="created_at")
     *
     * @var datetime
     */
    private $createdAt;
    
    /**
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime", name="updated_at")
     *
     * @var datetime
     */
    private $updatedAt;

    /**
     * @ORM\ManyToOne(targetEntity="\CatMS\AdminBundle\Entity\ContentGroup", inversedBy="contents")
     * @ORM\JoinColumn(name="content_group_id", referencedColumnName="id", onDelete="SET NULL")
     * @var object \CatMS\AdminBundle\Entity\ContentGroup
     */
    private $contentGroup;
    
    /**
     * @ORM\OneToMany(targetEntity="CatMS\AdminBundle\Entity\ContentArchive", mappedBy="content", cascade={"persist", "remove"})
     * @var object \CatMS\AdminBundle\Entity\ContentArvhive
     */
    private $archives;
    
    /**
     *  @ORM\Column(type="integer", nullable=true)
     */
    protected $priority = 99;
    
    protected $image;

    
    public function __construct() {
        $this->archives = new \Doctrine\Common\Collections\ArrayCollection(); 
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
     * @return ContentManager
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
     * Set title
     *
     * @param string $title
     * @return ContentManager
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set shortText
     *
     * @param string $shortText
     * @return ContentManager
     */
    public function setShortText($shortText)
    {
        $this->shortText = $shortText;

        return $this;
    }

    /**
     * Get shortText
     *
     * @return string 
     */
    public function getShortText()
    {
        return $this->shortText;
    }

    /**
     * Set fullText
     *
     * @param string $fullText
     * @return ContentManager
     */
    public function setFullText($fullText)
    {
        $this->fullText = $fullText;

        return $this;
    }

    /**
     * Get fullText
     *
     * @return string 
     */
    public function getFullText()
    {
        return $this->fullText;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return ContentManager
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
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     * @return ContentManager
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime 
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return ContentManager
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
     * Set contentGroup
     *
     * @param \CatMS\AdminBundle\Entity\ContentGroup $contentGroup
     * @return ContentManager
     */
    public function setContentGroup(\CatMS\AdminBundle\Entity\ContentGroup $contentGroup = null)
    {
        $this->contentGroup = $contentGroup;

        return $this;
    }

    /**
     * Get contentGroup
     *
     * @return \CatMS\AdminBundle\Entity\ContentGroup 
     */
    public function getContentGroup()
    {
        return $this->contentGroup;
    }

    /**
     * Set priority
     *
     * @param integer $priority
     * @return ContentManager
     */
    public function setPriority($priority)
    {
        $this->priority = $priority;

        return $this;
    }

    /**
     * Get priority
     *
     * @return integer 
     */
    public function getPriority()
    {
        return $this->priority;
    }
    
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }
    
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Add archives
     *
     * @param \CatMS\AdminBundle\Entity\ContentArchive $archives
     * @return ContentManager
     */
    public function addArchive(\CatMS\AdminBundle\Entity\ContentArchive $archives)
    {
        $this->archives[] = $archives;

        return $this;
    }

    /**
     * Remove archives
     *
     * @param \CatMS\AdminBundle\Entity\ContentArchive $archives
     */
    public function removeArchive(\CatMS\AdminBundle\Entity\ContentArchive $archives)
    {
        $this->archives->removeElement($archives);
    }

    /**
     * Get archives
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getArchives()
    {
        return $this->archives;
    }
}
