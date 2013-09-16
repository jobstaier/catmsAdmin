<?php

namespace CatMS\FrontendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use CatMS\FrontendBundle\Resources\CommonMethods;

class AssetsController extends Controller
{
    public function nzozAssetsAction()
    {
        $em = $this->getDoctrine()->getManager();
        $common = new CommonMethods($em);
        
        return $this->render(
            'CatMSFrontendBundle:Assets:assets.html.twig', 
            array(
                'content' => $common->getContent('nzoz-assets-content'),
                'assets' => $common->getImageGroup('nzoz-assets'),
                'suf' => 'nzoz'
            )
        );        
    }
    
    public function hospAssetsAction()
    {
        $em = $this->getDoctrine()->getManager();
        $common = new CommonMethods($em);
        
        return $this->render(
            'CatMSFrontendBundle:Assets:assets.html.twig', 
            array(
                'content' => $common->getContent('hosp-assets-content'),
                'assets' => $common->getImageGroup('hosp-assets'),
                'suf' => 'hosp'
            )
        );          
    }    
}
