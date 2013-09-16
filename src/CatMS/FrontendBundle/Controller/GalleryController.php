<?php

namespace CatMS\FrontendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use CatMS\FrontendBundle\Resources\CommonMethods;

class GalleryController extends Controller
{
    public function nzozGalleryAction($page)
    {
        $em = $this->getDoctrine()->getManager();
        $common = new CommonMethods($em);
        $slug = 'galeria';
        
        return $this->render(
            'CatMSFrontendBundle:Gallery:gallery.html.twig', 
            array(
                'pagination' => $this->getPagination(
                    'nzoz-gallery-order-by-priority', 
                    $common, 
                    $em, 
                    $slug,
                    $page,
                    'nzoz-gallery-records-list-page'
                ),
                'slug' => $slug 
            )
        );        
    }
    
    public function hospGalleryAction($slug, $page)
    {
        $em = $this->getDoctrine()->getManager();
        $common = new CommonMethods($em);   

        return $this->render(
            'CatMSFrontendBundle:Gallery:hosp-gallery.html.twig', 
            array(
                'pagination' => $this->getPagination(
                    'hosp-gallery-order-by-priority', 
                    $common, 
                    $em, 
                    $slug,
                    $page,
                    'hosp-gallery-records-list-page'
                ),
                'slug' => $slug 
            )
        ); 
    }   
    
    private function getPagination($settingSlug, $common, $em, $slug, $page, $settingPages)
    {
        $dql = (
            $common->getSetting($settingSlug) ? 
                "SELECT cm FROM CatMSAdminBundle:ContentManager cm 
                JOIN cm.contentGroup cg 
                WHERE cg.slug = :slug 
                ORDER BY cm.priority ASC"
                :
                "SELECT cm FROM CatMSAdminBundle:ContentManager cm 
                JOIN cm.contentGroup cg 
                WHERE cg.slug = :slug 
                ORDER BY cm.createdAt DESC"
        );        
        
        $query = $em->createQuery($dql)->setParameter('slug', $slug);

        $recordsPerPage = $common->getSetting($settingPages);

        $paginator  = $this->get('knp_paginator');

        $pagination = $paginator->paginate($query, 
            $this->get('request')->query->get('page', $page),
            $recordsPerPage            
        );   
        
        return $pagination;
    }    
}
