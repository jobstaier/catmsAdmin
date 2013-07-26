<?php

namespace CatMS\AdminBundle\Twig;

use Symfony\Component\HttpFoundation\Request;

class TwigUserExtension extends \Twig_Extension {

    protected $request;
    protected $environment;
    protected $em;

    public function __construct(Request $request, \Doctrine\ORM\EntityManager $em) {
        $this->request = $request;
        $this->em = $em;
    }
    
    public function initRuntime(\Twig_Environment $environment) {
        $this->environment = $environment;
    }

    public function getFilters() {
        return array(
            new \Twig_SimpleFilter('parse_image', array($this, 'parseImageFilter')),
            new \Twig_SimpleFilter('stripp', array($this, 'strippFilter')),
        );
    }

    public function getFunctions() {
        return array(
            'truncate' => new \Twig_Function_Method($this, 'truncateFunction'),
            'nl2br' => new \Twig_Function_Method($this, 'nl2brFunction'),
            'get_controller_name' => new \Twig_Function_Method($this, 'getControllerNameFunction'),
            'get_action_name' => new \Twig_Function_Method($this, 'getActionNameFunction'),
            'is_active_nav_button' => new \Twig_Function_Method($this, 'getIsActiveNavButtonFunction'),
            'render_image_with_redirect' => new \Twig_Function_Method($this, 'renderImageWithRedirectFunction'),
            'get_image_size' => new \Twig_Function_Method($this, 'getImageSizeFunction'),
            'ceil' => new \Twig_Function_Method($this, 'ceilFunction'),
            'number_format' => new \Twig_Function_Method($this, 'numberFormatFunction'),
            'get_product_image_size' => new \Twig_Function_Method($this, 'getProductImageSizeFunction'),
            'get_entity_main_image_path' => new \Twig_Function_Method($this, 'getEntityMainImagePathFunction'),
            'get_enity_main_image' => new \Twig_Function_Method($this, 'getEnityMainImageFunction'),
            'var_dump' => new \Twig_Function_Method($this, 'varDumpFunction'),
            'get_custom_banner' => new \Twig_Function_Method($this, 'getCustomBannerFunction'),
            'get_setting' => new \Twig_Function_Method($this, 'getSettingFunction'),
            'get_seo' => new \Twig_Function_Method($this, 'getSeoFunction'),
            'get_content_group' => new \Twig_Function_Method($this, 'getContentGroupFunction'),
            'get_content' => new \Twig_Function_Method($this, 'getContentFunction'),
            'get_image' => new \Twig_Function_Method($this, 'getImageFunction'),
        );
    }

    public function truncateFunction($str, $maxChars) {
        if (strlen($str) > ($maxChars - 3)) {
            return mb_substr($str, 0, $maxChars, 'UTF-8') . ' [...]';
        } else {
            return $str;
        }
    }

    public function nl2brFunction($string) {
        return nl2br($string);
    }

    public function getName() {
        return 'twig_user_extension';
    }

    public function getControllerNameFunction() {
        $pattern = "#Controller\\\([a-zA-Z]*)Controller#";
        $matches = array();
        preg_match($pattern, $this->request->get('_controller'), $matches);

        if (isset($matches[1])) {
            return strtolower($matches[1]);
        } else {
            return null;
        }
    }

    public function getActionNameFunction() {
        $pattern = "#::([a-zA-Z]*)Action#";
        $matches = array();
        preg_match($pattern, $this->request->get('_controller'), $matches);

        if (isset($matches[1])) {
            return $matches[1];
        } else {
            return null;
        }
    }
    
    public function parseImageFilter($content)
    {
        $pattern = "(#img=([0-9])+#)";
        $matches = array();
        preg_match_all($pattern, $content, $matches);

        if ($matches) {
            foreach($matches[0] as $key => $code) {
                    preg_match("([0-9]+)", $code, $idMatch);
                    $id = $idMatch[0];

                    $image = $this->em->getRepository('CatMSAdminBundle:ImageUpload')->find($id);

                    $imgHtml = $this->renderImageWithRedirectFunction($image);

                    $parsedContent = str_replace($code, $imgHtml, $content);
                    
                    $content = $parsedContent;
            }
        } else {
            return $content;
        }
        return $content;

    }
    
    public function numberFormatFunction($number, $round)
    {
        return number_format($number, $round);
    }
    
    public function getIsActiveNavButtonFunction($controllerName, $actionName) 
    {
        $controller = $this->getControllerNameFunction();       
        $action = $this->getActionNameFunction();

        if ($controller == $controllerName && $action == $actionName) {
            return true;
        } else {
            return false;
        }
    }
    
    /**
     * 
     * @param \CatMS\AdminBundle\Entity\ImageUpload $image
     * @param string $style
     * @return type
     */
    public function renderImageWithRedirectFunction($image, $class = null, $style = null)
    {        
        if ($image) {
            $title = ($image->getTitle()) ? $image->getTitle() : '#';
            $styleHtml = ($style['style']) ? 'style="'.$style['style'].'"' : '';

            if ($image->getRedirect()) {
                return '<a '.$styleHtml.' href="'.$image->getRedirect().'" title="'.$image->getTitle().'"><img src="'.$this->request->getBasePath().'/'.$image->getWebPath().'" alt="'.$title.'"/></a>';
            } else {
                return '<img class="'.$class.'" '.$styleHtml.' src="'.$this->request->getBasePath().'/'.$image->getWebPath().'" alt="'.$title.'" />';
            } 
        } else {
            return null;
        }

    }
    
    public function getImageSizeFunction(\CatMS\AdminBundle\Entity\ImageUpload $image) {
        if (is_object($image) && file_exists($image->getAbsolutePath())) {
            $size = round(filesize($image->getAbsolutePath()) / 1024);
            switch ($size) {
                case $size < 100 :
                        return '<span class="label label-success">'.$size.' KB</span>';
                    break;
                case ($size >= 100 && $size < 300) :
                        return '<span class="label label-warning">'.$size.' KB</span>';
                    break;
                case $size > 300 :
                        return '<span class="label label-important">'.$size.' KB</span>';
                    break;
                default:
                    break;
            }    
        }
    }
    
    public function getProductImageSizeFunction(\CatMS\ShopBundle\Entity\Image $image) {
        if (is_object($image)) {
            $size = round(filesize($image->getAbsolutePath()) / 1024);
            switch ($size) {
                case $size < 100 :
                        return '<span class="label label-success">'.$size.' KB</span>';
                    break;
                case ($size >= 100 && $size < 300) :
                        return '<span class="label label-warning">'.$size.' KB</span>';
                    break;
                case $size > 300 :
                        return '<span class="label label-important">'.$size.' KB</span>';
                    break;
                default:
                    break;
            }    
        }
    }
    
    public function getEntityMainImagePathFunction(\Doctrine\ORM\EntityManager $em, $product)
    {
        $dql   = "SELECT i FROM CatMSShopBundle:Image i JOIN i.product p WHERE p.id = :product ORDER BY i.priority";
        $query = $em->createQuery($dql)->setParameter('product', $product)->setFirstResult(0)->setMaxResults(1);
        $result = $query->getOneOrNullResult();

        return (is_object($result)) ? $result->getPath() : null;
    }
    
    public function getEnityMainImageFunction(\Doctrine\ORM\EntityManager $em, $product)
    {
        $dql   = "SELECT i FROM CatMSShopBundle:Image i JOIN i.product p WHERE p.id = :product ORDER BY i.priority";
        $query = $em->createQuery($dql)->setParameter('product', $product)->setFirstResult(0)->setMaxResults(1);
        $result = $query->getOneOrNullResult();

        return $result;
    }
    
    public function ceilFunction($number)
    {
        return ceil($number);
    }
    
    public function strippFilter($content)
    {
        return str_replace(array('<p>', '</p>'), array('', ''), $content);
    }
    
    public function varDumpFunction($var)
    {
        return var_dump($var);
    }
    
    public function getCustomBannerFunction()
    {
        $slug = 'custom-banner-bottom-'.$this->getControllerNameFunction().'-'.$this->getActionNameFunction();
        
        $dql   = "SELECT c FROM CatMSAdminBundle:ContentManager c WHERE c.slug = :slug";
        $query = $this->em->createQuery($dql)->setParameter('slug', $slug);
        $result = $query->getResult();

        return $result;
    }
    
    public function getSettingFunction($slug)
    {
        $setting = $this->em->getRepository('CatMSAdminBundle:Setting')->findOneBySlug($slug);
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
        $qb = $this->em->getRepository('CatMSAdminBundle:ContentManager')->createQueryBuilder('c');
        
        $results = $qb->join('c.contentGroup', 'cg')
                ->where('cg.slug = :slug')
                ->orderBy('c.priority', 'ASC')
                ->setParameter('slug', $slug)
                ->getQuery()
                ->getResult();
        
        
        
        if ($imageGroupSlug) {
            $qbi = $this->em->getRepository('CatMSAdminBundle:ImageUpload')->createQueryBuilder('i');
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
        $image = $this->em->getRepository('CatMSAdminBundle:ImageUpload')->findOneBySlug($slug);
        return $image;
    }
}

?>
