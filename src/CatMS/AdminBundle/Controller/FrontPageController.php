<?php

namespace CatMS\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;

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
        return $this->render('CatMSAdminBundle:FrontPage:index.html.twig', 
            array()
        );
    }
    
    public function setCurrentLocaleAction($locale)
    {
        return new Response(json_encode(array('result' => 'success')), 200, 
            array('Content-Type' => 'application/json')
        ); 
    }
}
