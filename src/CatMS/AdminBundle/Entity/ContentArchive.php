<?php
namespace CatMS\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\True;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Table(name="content_archive")
 * @ORM\Entity(repositoryClass="CatMS\AdminBundle\Repository\ContentArchiveRepository")
 * @ORM\Table(name="content_archive")
 */
class ContentArchive
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @var integer
     */
    private $id;
    
    /**
     * @ORM\Column(type="string", length=255, unique=false)
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
     * @ORM\Column(type="string", length=510, unique=false)
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min="5",
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
     * @ORM\ManyToOne(targetEntity="\CatMS\AdminBundle\Entity\ContentManager", inversedBy="archives")
     * @ORM\JoinColumn(name="content_id", referencedColumnName="id", onDelete="SET NULL")
     * @var object \CatMS\AdminBundle\Entity\ContentGroup
     */
    private $content;
    
    /**
     *  @ORM\Column(type="integer", nullable=true)
     */
    protected $priority;

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
     * @return ContentArchive
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
     * @return ContentArchive
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
     * @return ContentArchive
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
     * @return ContentArchive
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
     * @return ContentArchive
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
     * Set description
     *
     * @param string $description
     * @return ContentArchive
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
     * Set content
     *
     * @param \CatMS\AdminBundle\Entity\ContentManager $content
     * @return ContentArchive
     */
    public function setContent(\CatMS\AdminBundle\Entity\ContentManager $content = null)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return \CatMS\AdminBundle\Entity\ContentManager
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set priority
     *
     * @param integer $priority
     * @return ContentArchive
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
    
    public function setPopertiesFromContent(\CatMS\AdminBundle\Entity\ContentManager $archived)
    {
        $this->setContent($archived);
        $this->setDescription($archived->getDescription());
        $this->setFullText($archived->getFullText());
        $this->setPriority($archived->getPriority());
        $this->setShortText($archived->getShortText());
        $this->setSlug($archived->getSlug());
        $this->setTitle($archived->getTitle());
    }
    
    public function serialize()
    {
        $data = array();
        
        $data['fullText'] = $this->getFullText();
        $data['shortText'] = $this->getShortText();
        $data['title'] = $this->getTitle();
        $data['slug'] = $this->getSlug();
        $data['description'] = $this->getDescription();
        $data['priority'] = $this->getPriority();
        
        return $data;
    }
}
