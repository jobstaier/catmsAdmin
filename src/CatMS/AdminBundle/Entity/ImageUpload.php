<?php
namespace CatMS\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

require_once __DIR__.'/../External/WideImage/WideImage.php';

/**
 * @ORM\Entity(repositoryClass="CatMS\AdminBundle\Repository\ImageUploadRepository")
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="asset_upload")
 */
class ImageUpload
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    protected $name;
    
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Length(
     *      min = "2",
     *      max = "255",
     *      minMessage = "Title must be at least {{ limit }} characters length",
     *      maxMessage = "Title cannot be longer than {{ limit }} characters length"
     * )
     */
    protected $title;
    
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $slug;
    
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $path;    
    
    /**
     * @Assert\File(maxSize="6000000")
     */
    protected $file;
    
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $redirect;
    
    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime", name="uploaded_at")
     *
     * @var datetime
     */
    protected $uploadedAt;
    
    /**
     * @ORM\ManyToOne(targetEntity="\CatMS\AdminBundle\Entity\ImageGroup", inversedBy="images")
     * @ORM\JoinColumn(name="image_group_id", referencedColumnName="id", onDelete="SET NULL")
     * @var object \CatMS\AdminBundle\Entity\ImageGroup
     */
    protected $imageGroup;

    /**
     *  @ORM\Column(type="integer", nullable=true)
     */
    protected $priority = 99;
    
    protected $temp;
    
    protected $mimeType;
    
    protected $deleteForm;
    
    protected $systemThumbWidth;
    
    protected $systemThumbHeight;
    
    protected $thumbPath;

    public function getAbsolutePath()
    {
        return null === $this->path
            ? null
            : $this->getUploadRootDir().'/'.$this->path;
    }
    
    public function getSystemThumbAbsolutePath()
    {
        return null === $this->path
            ? null
            : __DIR__.'/../../../../web/'.$this->getSystemThumbDir().'/'.$this->getSystemThmbPrefix().$this->path;
    }
    
    public function getWebPath()
    {
        return null === $this->path
            ? null
            : $this->getUploadDir().'/'.$this->path;
    }
    
    public function getThumbWebPath()
    {
        return null === $this->path
            ? null
            : $this->getUploadDir().'/thmb_'.$this->path;
    }
    
    protected function getUploadRootDir()
    {
        // the absolute directory path where uploaded
        // documents should be saved
        return __DIR__.'/../../../../web/'.$this->getUploadDir();
    }
    
    protected function getUploadDir()
    {
        // get rid of the __DIR__ so it doesn't screw up
        // when displaying uploaded doc/image in the view.
        return 'uploads/media-library';
    }
    
    protected function getSystemThumbDir()
    {
        // get rid of the __DIR__ so it doesn't screw up
        // when displaying uploaded doc/image in the view.
        return 'uploads/system-thumbs';
    }

    public function setId($id)
    {
        $this->id = $id;
        return $this;
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
     * Set name
     *
     * @param string $name
     * @return ImageUpload
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
     * Set path
     *
     * @param string $path
     * @return ImageUpload
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Get path
     *
     * @return string 
     */
    public function getPath()
    {
        return $this->path;
    }
    
    /**
     * Sets file.
     *
     * @param UploadedFile $file
     */
    public function setFile(\Symfony\Component\HttpFoundation\File\UploadedFile $file = null)
    {
        $this->file = $file;
        // check if we have an old image path
        if (isset($this->path)) {
            // store the old name to delete after the update
            $this->temp = $this->path;
            $this->path = null;
        } else {
            $this->path = 'initial';
        }
    }
    
    /**
     * Get file.
     *
     * @return UploadedFile
     */
    public function getFile()
    {
        return $this->file;
    }
    
    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload()
    {
        if (null !== $this->getFile()) {
            if ($this->name != null) {
                $this->name = $this->slugify($this->name);
                $i = 0;
                while (file_exists($this->getUploadRootDir().'/'.$this->name.'.'.$this->getFile()->guessExtension())) {
                   $i++;
                   $this->setName($this->name.'-'.$i);
                }
                $filename = $this->name;
            } else {
                $filename = substr(sha1(uniqid(mt_rand(), true)), 0, 10);
            }
            $this->path = $filename.'.'.$this->getFile()->guessExtension();
            $this->thumbPath = 'thmb_'.$filename.'.'.$this->getFile()->guessExtension();
        }
    }    
    
    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload()
    {
        if (null === $this->getFile()) {
            return;
        }
        
        $this->mimeType = $this->getFile()->getMimeType();
        
        $this->getFile()->move($this->getUploadRootDir(), $this->path);

        $this->resizeAndSaveImage();
        
        if ($this->imageGroup->getHasThumbnails()) {
            $this->resizeAndSaveThumb();
        }
        
        // check if we have an old image
        if (isset($this->temp)) {
            // delete the old image
            if (file_exists($this->getUploadRootDir().'/'.$this->temp)) {
                unlink($this->getUploadRootDir().'/'.$this->temp);
            }
            // clear the temp image path
            $this->temp = null;
        }
        $this->file = null;
    }    
    
    private function resizeAndSaveImage()
    {        
        if ($this->mimeType == 'image/jpeg' || $this->mimeType == 'image/png') {
            $imagePath = $this->getUploadRootDir().'/'.$this->path;
            $image = \WideImage::load($imagePath);
            $size = getimagesize($imagePath);

            $groupImageWidth = $this->getImageGroup()->getImageWidth();
            $groupImageHeight = $this->getImageGroup()->getImageHeight();

            if ($groupImageWidth || $groupImageHeight) {

                $width = ($groupImageWidth) ? $groupImageWidth : $size[0];
                $height = ($groupImageHeight) ? $groupImageHeight : $size[1];

                if ($size[0] > $groupImageWidth  || $size[1] > $groupImageHeight) {
                    $image->resize($width, $height, 'outside')
                        ->crop('center', 'center', $width, $height)
                        ->saveToFile($imagePath);
                }
            }
            
            //Save system thumb
            $sThmWidth = $this->getSystemThumbWidth();
            $sThmbHeight = $this->getSystemThumbHeight();
            
            $image->resize($sThmWidth, $sThmbHeight, 'outside')
                ->crop('center', 'center', $sThmWidth, $sThmbHeight)
                ->saveToFile($this->getSystemThumbDir().'/'.$this->getSystemThmbPrefix().$this->path);
            
        }
    }
    
    private function resizeAndSaveThumb()
    {        
        if ($this->mimeType == 'image/jpeg' || $this->mimeType == 'image/png') {
            $imagePath = $this->getUploadRootDir().'/'.$this->path;
            $imageThumbPath = $this->getUploadRootDir().'/'.$this->thumbPath;
            $image = \WideImage::load($imagePath);
            
            $groupImageWidth = ($this->getImageGroup()->getThumbnailWidth()) ? $this->getImageGroup()->getThumbnailWidth() : 150;
            $groupImageHeight = ($this->getImageGroup()->getThumbnailHeight()) ? $this->getImageGroup()->getThumbnailHeight() : 100;

            $image->resize($groupImageWidth, $groupImageHeight, 'outside')
                ->crop('center', 'center', $groupImageWidth, $groupImageHeight)
                ->saveToFile($imageThumbPath);

        }
    }
    
    /**
     * @ORM\PostRemove()
     */
    public function removeUpload()
    {
        if ($file = $this->getAbsolutePath()) {
            if (file_exists($file)) {
                unlink($file);
            }           
        }
        
        if ($systemThumb = $this->getSystemThumbAbsolutePath()) {
            if (file_exists($systemThumb)) {
                unlink($systemThumb);
            }           
        }      
        
        if ($thumb = $this->getThumbWebPath()) {
            if (file_exists($thumb)) {
                unlink($thumb);
            }    
        }
    }    

    /**
     * Set title
     *
     * @param string $title
     * @return ImageUpload
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
     * Set uploadedAt
     *
     * @param \DateTime $uploadedAt
     * @return ImageUpload
     */
    public function setUploadedAt($uploadedAt)
    {
        $this->uploadedAt = $uploadedAt;

        return $this;
    }

    /**
     * Get uploadedAt
     *
     * @return \DateTime 
     */
    public function getUploadedAt()
    {
        return $this->uploadedAt;
    }

    /**
     * Set imageGroup
     *
     * @param \CatMS\AdminBundle\Entity\ImageGroup $imageGroup
     * @return ImageUpload
     */
    public function setImageGroup(\CatMS\AdminBundle\Entity\ImageGroup $imageGroup = null)
    {
        $this->imageGroup = $imageGroup;

        return $this;
    }

    /**
     * Get imageGroup
     *
     * @return \CatMS\AdminBundle\Entity\ImageGroup 
     */
    public function getImageGroup()
    {
        return $this->imageGroup;
    }
    
    /**
     * Set priority
     *
     * @param integer $priority
     * @return ImageGroup
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

    /**
     * Set slug
     *
     * @param string $slug
     * @return ImageUpload
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
     * Set redirect
     *
     * @param string $redirect
     * @return ImageUpload
     */
    public function setRedirect($redirect)
    {
        $this->redirect = $redirect;

        return $this;
    }

    /**
     * Get redirect
     *
     * @return string 
     */
    public function getRedirect()
    {
        return $this->redirect;
    }
    
    public function getMimeType()
    {
        if (file_exists($this->getUploadRootDir().'/'.$this->path)) {
            $file = new \Symfony\Component\HttpFoundation\File\File($this->getUploadRootDir().'/'.$this->path);
            return $file->getMimeType();           
        } else {
            return null;
        }
    }
    
    public function setDeleteForm($deleteForm)
    {
        $this->deleteForm = $deleteForm;

        return $this;
    }

    public function getDeleteForm()
    {
        return $this->deleteForm;
    }
    
    public function serialize()
    {
        return array(
            'id' => $this->getId(),
            'name' => $this->getName(),
            'slug' => $this->getSlug(),
            'path' => $this->getPath(),
            'redirect' => $this->getRedirect(),
            'imageGroup' => $this->getImageGroup()->getSlug(),
            'imageGroupId' => $this->getImageGroup()->getId(),
            'uploadedAt' => $this->getUploadedAt(),
            'thumb' => $this->getSystemThumbDir().'/'.$this->getSystemThmbPrefix().$this->path,
            'mimeType' => $this->getMimeType(),
            'title' => $this->getTitle()
        );
    }
    
    public function getSystemThumbWidth() 
    {
        return 120;
    }
    
    public function getSystemThumbHeight() 
    {
        return 80;
    }
    
    public function getSystemThmbPrefix()
    {
        return 'sThmb_';
    }
    
    public function slugify($text)
    {
        // replace non letter or digits by -
        $text = preg_replace('~[^\\pL\d]+~u', '-', $text);

        // trim
        $text = trim($text, '-');

        // transliterate
        if (function_exists('iconv'))
        {
            $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
        }

        // lowercase
        $text = strtolower($text);

        // remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);

        if (empty($text))
        {
            return 'n-a';
        }

        return $text;
    }
}
