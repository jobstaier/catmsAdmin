<?php

namespace CatMS\FrontendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class PrimaryController extends Controller
{
    public function indexAction()
    {
        return $this->render(
            'CatMSFrontendBundle:Primary:index.html.twig', 
            array(

            )
        );
    }
}
