<?php

namespace CatMS\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="CatMS\AdminBundle\Repository\ContentFieldsRepository")
 * @ORM\Table(name="content_fields")
 * @ORM\HasLifecycleCallbacks
 */
class ContentFields
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @ORM\Column(type="boolean", 
     *  name="has_description",  
     *  unique=false, 
     *  nullable=false
     * )
     * @var string
     */    
    private $hasDescription = true;
    
    /**
     * @ORM\Column(type="boolean", 
     *  name="has_title",  
     *  unique=false, 
     *  nullable=false
     * )
     * @var string
     */    
    private $hasTitle = false;
    
    /**
     * @ORM\Column(type="boolean", 
     *  name="has_short_text",  
     *  unique=false, 
     *  nullable=false
     * )
     * @var string
     */    
    private $hasShortText = false;
    
    /**
     * @ORM\Column(type="boolean", 
     *  name="has_full_text",  
     *  unique=false, 
     *  nullable=false
     * )
     * @var string
     */    
    private $hasFullText = true;

    /**
     * @ORM\Column(type="text", 
     *  name="label_description",  
     *  unique=false, 
     *  nullable=false
     * )
     * @Assert\Length(
     *      min="3",
     *      minMessage="This value must have at least {{ limit }} characters"
     * ) 
     * @var string
     */
    private $fieldDescriptionLabel;
    
    /**
     * @ORM\Column(type="text", 
     *  name="label_short_text", 
     *  unique=false, 
     *  nullable=false
     * )
     * @Assert\Length(
     *      min="3",
     *      minMessage="This value must have at least {{ limit }} characters"
     * ) 
     * @var string
     */
    private $fieldShortTextLabel;
        
    /**
     * @ORM\Column(type="text", 
     *  name="label_title", 
     *  unique=false, 
     *  nullable=false
     * )
     * @Assert\Length(
     *      min="3",
     *      minMessage="This value must have at least {{ limit }} characters"
     * ) 
     * @var string
     */    
    private $fieldTitleLabel;
    
    /**
     * @ORM\Column(type="text", 
     *  name="label_full_text", 
     *  unique=false, 
     *  nullable=false
     * )
     * @Assert\Length(
     *      min="3",
     *      minMessage="This value must have at least {{ limit }} characters"
     * ) 
     * @var string
     */
    private $fieldFullTextLabel;    

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
     * Set hasDescription
     *
     * @param boolean $hasDescription
     * @return ContentFields
     */
    public function setHasDescription($hasDescription)
    {
        $this->hasDescription = $hasDescription;

        return $this;
    }

    /**
     * Get hasDescription
     *
     * @return boolean 
     */
    public function getHasDescription()
    {
        return $this->hasDescription;
    }

    /**
     * Set hasTitle
     *
     * @param boolean $hasTitle
     * @return ContentFields
     */
    public function setHasTitle($hasTitle)
    {
        $this->hasTitle = $hasTitle;

        return $this;
    }

    /**
     * Get hasTitle
     *
     * @return boolean 
     */
    public function getHasTitle()
    {
        return $this->hasTitle;
    }

    /**
     * Set hasShortText
     *
     * @param boolean $hasShortText
     * @return ContentFields
     */
    public function setHasShortText($hasShortText)
    {
        $this->hasShortText = $hasShortText;

        return $this;
    }

    /**
     * Get hasShortText
     *
     * @return boolean 
     */
    public function getHasShortText()
    {
        return $this->hasShortText;
    }

    /**
     * Set fieldDescriptionLabel
     *
     * @param string $fieldDescriptionLabel
     * @return ContentFields
     */
    public function setFieldDescriptionLabel($fieldDescriptionLabel)
    {
        $this->fieldDescriptionLabel = ($fieldDescriptionLabel != '') ?
                $fieldDescriptionLabel : 'Description';

        return $this;
    }

    /**
     * Get fieldDescriptionLabel
     *
     * @return string 
     */
    public function getFieldDescriptionLabel()
    {
        return $this->fieldDescriptionLabel;
    }

    /**
     * Set fieldShortTextLabel
     *
     * @param string $fieldShortTextLabel
     * @return ContentFields
     */
    public function setFieldShortTextLabel($fieldShortTextLabel)
    {
        $this->fieldShortTextLabel = ($fieldShortTextLabel != '') ?
                $fieldShortTextLabel : 'Short Text';

        return $this;
    }

    /**
     * Get fieldShortTextLabel
     *
     * @return string 
     */
    public function getFieldShortTextLabel()
    {
        return $this->fieldShortTextLabel;
    }

    /**
     * Set fieldTitleLabel
     *
     * @param string $fieldTitleLabel
     * @return ContentFields
     */
    public function setFieldTitleLabel($fieldTitleLabel)
    {
        $this->fieldTitleLabel = ($fieldTitleLabel != '') ?
                $fieldTitleLabel : 'Title';
        
        return $this;
    }

    /**
     * Get fieldTitleLabel
     *
     * @return string 
     */
    public function getFieldTitleLabel()
    {
        return $this->fieldTitleLabel;
    }

    /**
     * Set hasFullText
     *
     * @param boolean $hasFullText
     * @return ContentFields
     */
    public function setHasFullText($hasFullText)
    {
        $this->hasFullText = $hasFullText;

        return $this;
    }

    /**
     * Get hasFullText
     *
     * @return boolean 
     */
    public function getHasFullText()
    {
        return $this->hasFullText;
    }

    /**
     * Set fieldFullTextLabel
     *
     * @param string $fieldFullTextLabel
     * @return ContentFields
     */
    public function setFieldFullTextLabel($fieldFullTextLabel)
    {
         $this->fieldFullTextLabel = ($fieldFullTextLabel != '') ?
                $fieldFullTextLabel : 'Full Text';

        return $this;
    }

    /**
     * Get fieldFullTextLabel
     *
     * @return string 
     */
    public function getFieldFullTextLabel()
    {
        return $this->fieldFullTextLabel;
    }
}
