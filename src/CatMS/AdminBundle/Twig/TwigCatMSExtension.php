<?php

namespace CatMS\AdminBundle\Twig;

use Symfony\Component\HttpFoundation\Request;

class TwigCatMSExtension extends \Twig_Extension {
    
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
    
    public function getName() {
        return 'twig_catms_admin_extension';
    }

    public function getFilters() {
        return array(
            new \Twig_SimpleFilter('parse_image', array($this, 'parseImageFilter')),
            new \Twig_SimpleFilter('parse_gallery', array($this, 'parseGalleryFilter')),
        );
    }

    public function getFunctions() {
        return array(
            'var_dump' => new \Twig_Function_Method($this, 'varDumpFunction'),
            'get_controller_name' => new \Twig_Function_Method($this, 'getControllerNameFunction'),
            'get_action_name' => new \Twig_Function_Method($this, 'getActionNameFunction'),
            'is_active_nav_button' => new \Twig_Function_Method($this, 'getIsActiveNavButtonFunction'),
            'render_image_with_redirect' => new \Twig_Function_Method($this, 'renderImageWithRedirectFunction'),
            'get_image_size' => new \Twig_Function_Method($this, 'getImageSizeFunction')
        );
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
    
    public function varDumpFunction($var)
    {
        return var_dump($var);
    }
    
    public function parseGalleryFilter($content)
    {
        $pattern = "(#gallery=([a-zA-Z0-9_-])+#)";
        $matches = array();
        preg_match_all($pattern, $content, $matches);
        
		$tmpContent = '';
		
        if ($matches) {
            foreach($matches[0] as $key => $code) {

                preg_match("(\=[a-zA-Z0-9_-]+)", $code, $idMatch);
                    
                $id = $idMatch[0];
                    
                if (isset($idMatch[0])) {
                    $slug = ltrim($idMatch[0], '=');
                    $imageGroup = $this->em->getRepository('CatMSAdminBundle:ImageGroup')->findOneBySlug($slug);
                        
                    if (is_object($imageGroup)) {

                        if (count($imageGroup->getImages()) > 0) {
                            $tmpContent = '<div class="catms-gallery-container"><h2>'.$imageGroup->getDescription().'</h2><div class="thumbs">';

                            foreach ($imageGroup->getImages() as $imgId => $image) {
                                if ($imageGroup->getHasThumbnails()) {

                                    $frameHtml = 
                                        '<a class="colorbox-gallery" rel="'.$image->getImageGroup()->getSlug().'" href="'.$this->request->getBasePath().'/'.$image->getWebPath().'" alt="">'.
                                                '<img class="catms-img" src="'.$this->request->getBasePath().'/'.$image->getThumbWebPath().'" />'.
                                        '</a>';

                                } else {
                                    $width = ($image->getImageGroup()->getThumbnailWidth()) ? $image->getImageGroup()->getThumbnailWidth() : 150;
                                    $height = ($image->getImageGroup()->getThumbnailHeight()) ? $image->getImageGroup()->getThumbnailHeight() : 150;

                                    $frameHtml = 
                                            '<a class="colorbox-gallery" rel="'.$image->getImageGroup()->getSlug().'" href="'.$this->request->getBasePath().'/'.$image->getWebPath().'" alt="">'.
                                                '<img class="catms-img" style="max-width='.$width.'px; max-height='.$height.'px;" src="'.$this->request->getBasePath().'/'.$image->getThumbWebPath().'" />'.
                                            '</a>';
                                }

                                $tmpContent .= $frameHtml;
                            }

                            $tmpContent .= 
                                '<script>'.
                                    '$(function(){'.
                                            '$("a.colorbox-gallery").colorbox({});'.
                                     '})'.
                                '</script>'.
                            '</div></div>'; 
                        }

                    }
						
                }
                    
                $parsedContent = str_replace($code, $tmpContent, $content);
                    
                $content = $parsedContent;
            }
        } else {
            return $content;
        }

        return $content;       
    }
    
}