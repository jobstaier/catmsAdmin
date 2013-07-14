<?php

namespace CatMS\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Admin front page controller.
 * 
 * @Route("/admin")
 */
class FrontPageController extends Controller
{
    /**
     * @Route("/", name="admin-home")
     */
    public function adminAction()
    {
        return $this->render('CatMSAdminBundle:FrontPage:index.html.twig', array(
            
        ));
    }
}
