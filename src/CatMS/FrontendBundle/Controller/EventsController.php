<?php

namespace CatMS\FrontendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use CatMS\FrontendBundle\Resources\CommonMethods;

class EventsController extends Controller
{
    public function eventsPageAction($slug, $page)
    {
        $em = $this->getDoctrine()->getManager();
        $common = new CommonMethods($em);
        
        $group = $common->getGroup('wydarzenia');
        $content = $common->getContent($slug);
        
        $subpages = $common->getContentGroup('wydarzenia');

        $pagination = $this->getPagination($slug, $em, $page);

        return $this->render(
            'CatMSFrontendBundle:Events:page.html.twig',
            array(
                'group' => $group,
                'page' => $subpages,
                'content' => $content,
                'subpages' => $subpages,
                'pagesWithLead' => $this->getPagesWithLead(),
                'pagination' => $pagination,
                'currentPage' => $page
            )
        );         
    }      
    
    public function eventsPageDetailsAction($slug, $contentSlug)
    {
        $em = $this->getDoctrine()->getManager();
        $common = new CommonMethods($em);        
        
        $content = $common->getContent($contentSlug);
        
        return $this->render(
            'CatMSFrontendBundle:Events:page-with-lead-details.html.twig',
            array(
                'content' => $content,
                'slug' => $slug
            )
        );         
    }
    
    private function getPagesWithLead()
    {
        return array(
            'artykuly', 
            'kalendarium-wydarzen', 
            'my-w-mediach'
        );
    }
    
    private function getPagination($slug, $em, $page) {
         
        $repository = $this->getDoctrine()
            ->getRepository('CatMSAdminBundle:ContentManager');
        
        $query = $repository->createQueryBuilder('cm')
                ->select('COUNT(cm.id)')
                ->join('cm.contentGroup', 'cg')
                ->where('cg.slug = :slug ')
                ->setParameter('slug', $slug)
                ->getQuery();
        
        $count = $query->getSingleScalarResult();
        
        $common = new CommonMethods($em);
        $recordsPerPage = $common->getSetting('events-records-list-page');
        
        if ($count / $recordsPerPage < $page) {
            return false;
        }
                
        if (in_array($slug, $this->getPagesWithLead())) {
            
            $dql = "SELECT cm FROM CatMSAdminBundle:ContentManager cm 
                    JOIN cm.contentGroup cg 
                    WHERE cg.slug = :slug 
                    ORDER BY cm.createdAt DESC";

            $query = $em->createQuery($dql)->setParameter('slug', $slug);

            
            $recordsPerPage = $common->getSetting('events-records-list-page');
           
            $paginator  = $this->get('knp_paginator');
            
            $pagination = $paginator->paginate(
                $query, 
                $this->get('request')->query->get('page', $page),
                $recordsPerPage            
            );   

            return $pagination;
            
        } else {
            return false;
        }
    }
}
