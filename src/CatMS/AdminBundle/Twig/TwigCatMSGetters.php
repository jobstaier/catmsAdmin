<?php

namespace CatMS\AdminBundle\Twig;

use Symfony\Component\HttpFoundation\Request;

class TwigCatMSGetters extends \Twig_Extension {
    
    protected $em;
    
    public function __construct(\Doctrine\ORM\EntityManager $em) {
        $this->em = $em;
    }    
    
    public function getName() {
        return 'twig_catms_extension';
    }

    public function getFilters() {
        return array(
            
        );
    }

    public function getFunctions() {
        return array(
            'get_setting' => new \Twig_Function_Method($this, 'getSettingFunction'),
            'get_seo' => new \Twig_Function_Method($this, 'getSeoFunction'),
            'get_content_group' => new \Twig_Function_Method($this, 'getContentGroupFunction'),
            'get_content' => new \Twig_Function_Method($this, 'getContentFunction'),
            'get_image' => new \Twig_Function_Method($this, 'getImageFunction'),
        );
    }
    
    public function getSettingFunction($slug)
    {
        $setting = $this->em->getRepository('CatMSAdminBundle:Setting')
            ->findOneBySlug($slug);
        return ($setting != null) ? $setting->getValue() : null;
    }
    
    public function getSeoFunction($slug, $fieldType, $locale = null) 
    {
        $repo = $this->em->getRepository('CatMSAdminBundle:Seo');
        $seo = $repo->createQueryBuilder('s')
                ->where('s.slug = :slug')
                ->setParameter('slug', $slug)
                ->getQuery()
                ->getOneOrNullResult();
        
        if ($seo != null) {
            switch ($fieldType) {
                case 'title':
                    return $seo->getPageTitle();
                case 'description':
                    return $seo->getMetaDescription();
                case 'keywords':
                    return $seo->getMetaKeywords();
                case 'all':
                    return $seo;
            }
        } else {
            return null;
        }      
    }
        
    public function getContentGroupFunction($slug, $imageGroupSlug = null)
    {
        $qb = $this->em->getRepository('CatMSAdminBundle:ContentManager')
            ->createQueryBuilder('c');
        
        $results = $qb->join('c.contentGroup', 'cg')
                ->where('cg.slug = :slug')
                ->orderBy('c.priority', 'ASC')
                ->setParameter('slug', $slug)
                ->getQuery()
                ->getResult();
        
        
        
        if ($imageGroupSlug) {
            $qbi = $this->em->getRepository('CatMSAdminBundle:ImageUpload')
                ->createQueryBuilder('i');
            $images = $qbi->join('i.imageGroup', 'ig')
                ->where('ig.slug = :slug')
                ->orderBy('i.priority', 'ASC')
                ->setParameter('slug', $imageGroupSlug)
                ->getQuery()
                ->getResult();
            
            foreach ($results as &$result) {
                foreach($images as $image) {
                    if ($image->getPriority() == $result->getPriority()) {
                        $result->setImage($image);
                    }
                }
            }
            
        }

        return $results;
    }
    
    
    public function getContentFunction($slug, $fieldType = 'all', $locale = null)
    {
        $repo = $this->em->getRepository('CatMSAdminBundle:ContentManager');
        $content = $repo->createQueryBuilder('c')
                ->where('c.slug = :slug')
                ->setParameter('slug', $slug)
                ->getQuery()
                ->getOneOrNullResult();
        
        
        if ($content != null) {
            switch ($fieldType) {
                case 'fullText':
                    return $content->getFullText();
                case 'shortText':
                    return $content->getShortText();
                case 'title':
                    return $content->getTitle();
                case 'all':
                    return $content;
            }
        } else {
            return null;
        }  
    }
    
    public function getImageFunction($slug)
    {
        $image = $this->em->getRepository('CatMSAdminBundle:ImageUpload')
            ->findOneBySlug($slug);
        return $image;
    }
}
