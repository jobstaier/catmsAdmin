<?php

namespace CatMS\FrontendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use CatMS\FrontendBundle\Resources\CommonMethods;

class GroupPageController extends Controller
{
    public function nzozGroupPageAction($slug)
    {
        $em = $this->getDoctrine()->getManager();
        $common = new CommonMethods($em);
        
        $group = $common->getGroup($slug);
        
        $subpages = $common->getContentGroup($slug);
        
        return $this->render(
            'CatMSFrontendBundle:GroupPage:page.html.twig', 
            array(
                'group' => $group,
                'subpages' => $subpages,
                'header' => str_replace(
                    'NZOZ -', 
                    '', 
                    $group->getDescription()
                )
            )
        );        
    }
    
    public function hospGroupPageAction($slug)
    {
        $em = $this->getDoctrine()->getManager();
        $common = new CommonMethods($em);
        
        $group = $common->getGroup($slug);
        
        $subpages = $common->getContentGroup($slug);
        
        return $this->render(
            'CatMSFrontendBundle:GroupPage:page.html.twig', 
            array(
                'group' => $group,
                'subpages' => $subpages,
                'header' => str_replace(
                    'UM - Hospicjum -', 
                    '', 
                    $group->getDescription()
                )
            )
        );         
    }      
}
