<?php

namespace CatMS\FrontendBundle\Services;

class CommonMethods
{
    private $em;
    
    public function __construct(\Doctrine\ORM\EntityManager $em) {
        $this->em = $em;
    }
    
    public function getSeo($slug)
    {
        $seo = $this->em
            ->getRepository('CatMSAdminBundle:Seo')
            ->findOneBySlug($slug);
        
        return $seo;
    }
    
    public function getSetting($slug)
    {
        $setting = $this->em
            ->getRepository('CatMSAdminBundle:Setting')
            ->findOneBySlug($slug);
        
        return $setting->getValue();
    }
    
    public function getContent($slug, $locale = null)
    {
        $contentRepo = $this->em->getRepository('CatMSAdminBundle:ContentManager');
        $result = $contentRepo->createQueryBuilder('c')
            ->where('c.slug = :slug')
            ->setParameter('slug', $slug)
            ->getQuery()
            ->setHint(\Doctrine\ORM\Query::HINT_CUSTOM_OUTPUT_WALKER,
                'Gedmo\\Translatable\\Query\\TreeWalker\\TranslationWalker')
            ->setHint(\Gedmo\Translatable\TranslatableListener::HINT_TRANSLATABLE_LOCALE, $locale)
            ->setHint(\Gedmo\Translatable\TranslatableListener::HINT_FALLBACK, 1)
            ->getOneOrNullResult();
        
        return $result;
    }

    
    public function getSingleImage($slug)
    {
        $img = $this->em
            ->getRepository('CatMSAdminBundle:ImageUpload')
            ->findOneBySlug($slug);
        
        return $img;
    }
    
    public function getImageGroup($slug)
    {
        $images = $this->em
            ->getRepository('CatMSAdminBundle:ImageGroup')
            ->fetchGroupImages($slug);
        
        return $images;
    }
    
    public function getGroup($slug) 
    {   
        $group = $this->em
            ->getRepository('CatMSAdminBundle:ContentGroup')
            ->findOneBySlug($slug);
        
        return $group;
    }
    
    public function getContentGroup($slug, $locale = null)
    {
        $contentGroupRepo = $this->em->getRepository('CatMSAdminBundle:ContentManager');
        $results = $contentGroupRepo->createQueryBuilder('c')
            ->join('c.contentGroup', 'cg')
            ->where('cg.slug = :slug')
            ->orderBy('c.priority', 'ASC')
            ->setParameter('slug', $slug)
            ->getQuery()
            ->setHint(\Doctrine\ORM\Query::HINT_CUSTOM_OUTPUT_WALKER,
                'Gedmo\\Translatable\\Query\\TreeWalker\\TranslationWalker')
            ->setHint(\Gedmo\Translatable\TranslatableListener::HINT_TRANSLATABLE_LOCALE, $locale)
            ->setHint(\Gedmo\Translatable\TranslatableListener::HINT_FALLBACK, 1)
            ->getResult();

        return $results;
    }
    
    public function getContentGroupCreateOrder($slug, $locale = null)
    {
        $contentGroupRepo = $this->em->getRepository('CatMSAdminBundle:ContentManager');
        $results = $contentGroupRepo->createQueryBuilder('c')
            ->join('c.contentGroup', 'cg')
            ->where('cg.slug = :slug')
            ->orderBy('c.createdAt', 'DESC')
            ->setParameter('slug', $slug)
            ->getQuery()
            ->setHint(\Doctrine\ORM\Query::HINT_CUSTOM_OUTPUT_WALKER,
                'Gedmo\\Translatable\\Query\\TreeWalker\\TranslationWalker')
            ->setHint(\Gedmo\Translatable\TranslatableListener::HINT_TRANSLATABLE_LOCALE, $locale)
            ->setHint(\Gedmo\Translatable\TranslatableListener::HINT_FALLBACK, 1)
            ->getResult();

        return $results;
    }
    
    public function getContentGroupCreateOrderLimited($slug, $limit, $locale = null)
    {
    $contentGroupRepo = $this->em->getRepository('CatMSAdminBundle:ContentManager');
        $results = $contentGroupRepo->createQueryBuilder('c')
            ->join('c.contentGroup', 'cg')
            ->where('cg.slug = :slug')
            ->orderBy('c.createdAt', 'DESC')
            ->setParameter('slug', $slug)
            ->setFirstResult(0)
            ->setMaxResults($limit)
            ->getQuery()
            ->setHint(\Doctrine\ORM\Query::HINT_CUSTOM_OUTPUT_WALKER,
                'Gedmo\\Translatable\\Query\\TreeWalker\\TranslationWalker')
            ->setHint(\Gedmo\Translatable\TranslatableListener::HINT_TRANSLATABLE_LOCALE, $locale)
            ->setHint(\Gedmo\Translatable\TranslatableListener::HINT_FALLBACK, 1)
            ->getResult();

        return $results;
    }
}
?>
