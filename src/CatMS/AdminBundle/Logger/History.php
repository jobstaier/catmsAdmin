<?php
namespace CatMS\AdminBundle\Logger;

class History
{
    protected $session;
    protected $container;
    protected $router;
    protected $historyRecords;

    public function __construct(\Symfony\Component\HttpFoundation\Session\Session $session,
        \Symfony\Component\Routing\Router $router = null)
    {
        $this->historyRecords = 10;
        $this->session = $session;
        $this->container = ($this->session->get('history') !== null) ? $this->session->get('history') : array();
        $this->router = $router;

    }

    public function __destruct() {
        
    }
    
    public function logListContentGroup(\CatMS\AdminBundle\Entity\ContentGroup $group)
    {
        $url = $this->router->generate('content-manager-list', array('page' => 1, 'slug' => $group->getSlug()));
        $log = '<a href="'.$url.'">Show '.strip_tags($group->getDescription()).' group content</a>';
        $this->addToHistory($log);
    }    
    
    public function logOpenEditContent(\CatMS\AdminBundle\Entity\ContentManager $content)
    {
        $url = $this->router->generate('content-manager-edit', array('id' => $content->getId(), 'group' => $content->getContentGroup()->getSlug()));
        $log = '<a href="'.$url.'">Open '.strip_tags($content->getDescription()).' to edit</a>';
        $this->addToHistory($log);
    }
    
    public function logEditContent(\CatMS\AdminBundle\Entity\ContentManager $content)
    {
        $url = $this->router->generate('content-manager-edit', array('id' => $content->getId(), 'group' => $content->getContentGroup()->getSlug()));
        $log = '<a href="'.$url.'">Edit '.strip_tags($content->getDescription()).'</a>';
        $this->addToHistory($log);
    }
    
    public function logListImageGroup(\CatMS\AdminBundle\Entity\ImageGroup $group)
    {
        $url = $this->router->generate('media-library', array('page' => 1, 'slug' => $group->getSlug()));
        $log = '<a href="'.$url.'">Show '.strip_tags($group->getDescription()).' image content</a>';
        $this->addToHistory($log);
    }
    
    public function logListImage()
    {
        $url = $this->router->generate('media-library');
        $log = '<a href="'.$url.'">Show image list</a>';
        $this->addToHistory($log);
    }
    
    public function logOpenEditImage(\CatMS\AdminBundle\Entity\ImageUpload $image)
    {
        $url = $this->router->generate('media-library-image-edit', array('id' => $image->getId(), 'group' => $image->getImageGroup()->getSlug()));
        $log = '<a href="'.$url.'">Open image '.strip_tags($image->getTitle()).' to edit</a>';
        $this->addToHistory($log);
    }
    
    public function logEditImage(\CatMS\AdminBundle\Entity\ImageUpload $image)
    {
        $url = $this->router->generate('media-library-image-edit', array('id' => $image->getId(), 'group' => $image->getImageGroup()->getSlug()));
        $log = '<a href="'.$url.'">Edit image '.strip_tags($image->getTitle()).'</a>';
        $this->addToHistory($log);
    }
    
    public function logDeleteImage()
    {
        $log = 'Delete image';
        $this->addToHistory($log);
    }
    
    public function addToHistory($log)
    {
        $arr = array_reverse($this->container);
        $arr[] = $log;
        $this->container = array_slice(array_reverse($arr), 0, $this->historyRecords);
        $this->session->set('history', $this->container);
    }
    
    public function getHistory()
    {
        //return array_slice($this->container, 1,  $this->historyRecords);
        return $this->container;
    }
}
?>
