<?php

namespace CatMS\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use CatMS\AdminBundle\Utility\Gravatar;

/**
 * Admin front page controller.
 *
 */
class FrontPageController extends Controller
{
    /**
     * Admin frontpage
     * 
     */
    public function indexAction()
    {
        $gravatar = new Gravatar();
        
        
        return $this->render('CatMSAdminBundle:FrontPage:index.html.twig', 
            array(
                'gravatar' => $gravatar->getGravatar($this->getUser()->getEmail())
            )
        );
    }
    
    public function setCurrentLocaleAction($locale)
    {
        return new Response(json_encode(array('result' => 'success')), 200, 
            array('Content-Type' => 'application/json')
        ); 
    }
}
