<?php
namespace CatMS\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\True;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Table(name="seo")
 * @ORM\Entity(repositoryClass="CatMS\AdminBundle\Repository\SeoRepository")
 * @UniqueEntity("slug")
 */
class Seo
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
     *      min="2",
     *      max="255",
     *      minMessage="Seo slug must have at least {{ limit }} characters.",
     *      maxMessage="Seo slug must have no more than {{ limit }} characters."
     * ) 
     * @var string
     */
    private $slug;
    
    /**
     * @ORM\Column(type="string", length=510, unique=false, name="page_title", nullable=true)
     * @Assert\Length(
     *      min="2",
     *      max="510",
     *      minMessage="Title must have at least {{ limit }} characters.",
     *      maxMessage="Page title must have no more than {{ limit }} characters."
     * ) 
     * @var string
     */
    private $pageTitle;
    
    /**
     * @ORM\Column(type="string", length=510, unique=false, name="meta_description", nullable=true)
     * @Assert\Length(
     *      min="1",
     *      max="510",
     *      minMessage="Meta description must have at least {{ limit }} characters.",
     *      maxMessage="Meta description must have no more than {{ limit }} characters."
     * ) 
     * @var string
     */
    private $metaDescription;
    
    /**
     * @ORM\Column(type="string", length=510, unique=false, name="meta_keywords", nullable=true)
     * @Assert\Length(
     *      min="1",
     *      max="510",
     *      minMessage="Meta keywords must have at least {{ limit }} characters.",
     *      maxMessage="Meta keywords must have no more than {{ limit }} characters."
     * ) 
     * @var string
     */
    private $metaKeywords;
    
    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime", name="created_at")
     *
     * @var datetime
     */
    private $createdAt;

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
     * @return Seo
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
     * Set pageTitle
     *
     * @param string $pageTitle
     * @return Seo
     */
    public function setPageTitle($pageTitle)
    {
        $this->pageTitle = $pageTitle;

        return $this;
    }

    /**
     * Get pageTitle
     *
     * @return string 
     */
    public function getPageTitle()
    {
        return $this->pageTitle;
    }

    /**
     * Set metaDescription
     *
     * @param string $metaDescription
     * @return Seo
     */
    public function setMetaDescription($metaDescription)
    {
        $this->metaDescription = $metaDescription;

        return $this;
    }

    /**
     * Get metaDescription
     *
     * @return string 
     */
    public function getMetaDescription()
    {
        return $this->metaDescription;
    }

    /**
     * Set metaKeywords
     *
     * @param string $metaKeywords
     * @return Seo
     */
    public function setMetaKeywords($metaKeywords)
    {
        $this->metaKeywords = $metaKeywords;

        return $this;
    }

    /**
     * Get metaKeywords
     *
     * @return string 
     */
    public function getMetaKeywords()
    {
        return $this->metaKeywords;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Seo
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
}
