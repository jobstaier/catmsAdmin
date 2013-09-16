<?php

namespace CatMS\FrontendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use CatMS\FrontendBundle\Resources\CommonMethods;

class HospChildrenController extends Controller
{
    public function hospGroupPageAction($slug, $page)
    {
        $em = $this->getDoctrine()->getManager();
        $common = new CommonMethods($em);
        
        $group = $common->getGroup('nasze-dzieci');
        $content = $common->getContent($slug);
        
        $subpages = $common->getContentGroup('nasze-dzieci');
        
        $pagination = $this->getPagination($slug, $em, $page);

        return $this->render(
            'CatMSFrontendBundle:HospChildren:page.html.twig',
            array(
                'group' => $group,
                'page' => $subpages,
                'content' => $content,
                'subpages' => $subpages,
                'pagesWithLead' => $this->getPagesWithLead(),
                'pagination' => $pagination
            )
        );         
    }      
    
    public function hospGroupPageDetailsAction($slug, $contentSlug)
    {
        $em = $this->getDoctrine()->getManager();
        $common = new CommonMethods($em);        
        
        $content = $common->getContent($contentSlug);
        
        return $this->render(
            'CatMSFrontendBundle:HospChildren:page-with-lead-details.html.twig',
            array(
                'content' => $content,
                'slug' => $slug
            )
        );         
    }
    
    private function getPagesWithLead()
    {
        return array(
            'historia-dzieci', 
            'pozegnania', 
            'urodziny'
        );
    }
    
    private function getPagination($slug, $em, $page) {
        if (in_array($slug, $this->getPagesWithLead())) {

            $dql = "SELECT cm FROM CatMSAdminBundle:ContentManager cm 
                    JOIN cm.contentGroup cg 
                    WHERE cg.slug = :slug 
                    ORDER BY cm.createdAt DESC";

            $query = $em->createQuery($dql)->setParameter('slug', $slug);

            $common = new CommonMethods($em);
            $recordsPerPage = $common->getSetting('hosp-children-records-list-page');

            $paginator  = $this->get('knp_paginator');

            $pagination = $paginator->paginate($query, 
                $this->get('request')->query->get('page', $page),
                $recordsPerPage            
            );   
            
            return $pagination;
            
        } else {
            return false;
        }
    }
}
