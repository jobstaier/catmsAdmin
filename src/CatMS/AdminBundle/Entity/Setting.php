<?php
namespace CatMS\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Symfony\Component\Validator\Constraints\True;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Table(name="settings")
 * @ORM\Entity(repositoryClass="CatMS\AdminBundle\Repository\SettingRepository")
 * @UniqueEntity("slug")
 */
class Setting
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
     *      minMessage="Setting slug must have at least {{ limit }} characters.",
     *      maxMessage="Setting slug must have no more than {{ limit }} characters."
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
     * @ORM\Column(type="text", nullable=true)
     * @var string
     */
    private $value;
    
    /**
     * @ORM\Column(type="string", length=20, name="module_range")
     * @Assert\NotBlank()
     * @Assert\Choice(choices = {"panel", "frontend"}, message = "Choose a valid range")
     */
    private $range;
    
    /**
     * @ORM\Column(type="string", length=20, name="field_type")
     * @Assert\NotBlank()
     * @Assert\Choice(choices = {"text", "checkbox", "radio", "textarea"}, message = "Choose a valid field type")
     */
    private $fieldType;
    
    private $inlineEditForm;

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
     * @return Setting
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
     * Set value
     *
     * @param string $value
     * @return Setting
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return string 
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set range
     *
     * @param string $range
     * @return Setting
     */
    public function setRange($range)
    {
        $this->range = $range;

        return $this;
    }

    /**
     * Get range
     *
     * @return string 
     */
    public function getRange()
    {
        return $this->range;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Setting
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
    
    public function setInlineEditForm($form)
    {
        $this->inlineEditForm = $form;
    }
    
    public function getInlineEditForm()
    {
        return $this->inlineEditForm;
    }

    /**
     * Set fieldType
     *
     * @param string $fieldType
     * @return Setting
     */
    public function setFieldType($fieldType)
    {
        $this->fieldType = $fieldType;

        return $this;
    }

    /**
     * Get fieldType
     *
     * @return string 
     */
    public function getFieldType()
    {
        return $this->fieldType;
    }
}
