<?php

namespace CatMS\FrontendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use CatMS\FrontendBundle\Resources\CommonMethods;

class PrimaryController extends Controller
{
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $common = new CommonMethods($em);

        return $this->render(
            'CatMSFrontendBundle:Primary:index.html.twig', 
            array(
                'docs' => $common->getImageGroup('mainpage-docs'),
                'news' => $common->getContentGroupCreateOrderLimited(
                    'aktualnosci', 
                    $limit = $common->getSetting('homepage-news-max')
                ),
                'team' => $common->getContent('o-nas'),
                'slides' => $common->getImageGroup('slider')
            )
        );
    }
    
    public function newsDetailsAction($slug) 
    {
        $em = $this->getDoctrine()->getManager();
        $common = new CommonMethods($em);

        return $this->render(
            'CatMSFrontendBundle:Primary:news-details.html.twig', 
            array(
                'news' => $common->getContent($slug)
            )
        );        
    }
    
    public function newsAllAction($slug, $page) {
        $em = $this->getDoctrine()->getManager();
        $common = new CommonMethods($em);
        
        $dql = "SELECT cm FROM CatMSAdminBundle:ContentManager cm 
                JOIN cm.contentGroup cg WHERE cg.slug = :slug 
                ORDER BY cm.createdAt DESC";

        $query = $em->createQuery($dql)->setParameter('slug', $slug);

        $recordsPerPage = $common->getSetting('news-count-on-list');
        
        $paginator  = $this->get('knp_paginator');

        $pagination = $paginator->paginate($query, 
            $this->get('request')->query->get('page', $page),
            $recordsPerPage
        ); 
        
        $group = $common->getGroup($slug);
        
        return $this->render(
            'CatMSFrontendBundle:Primary:news-all.html.twig', 
            array(
                'pagination' => $pagination,
                'page' => $page,
                'header' => str_replace('NZOZ - ', '', $group->getDescription())
            )
        );          
    }
    
    public function simpleSubpageAction($slug) 
    {
        $em = $this->getDoctrine()->getManager();
        $common = new CommonMethods($em);

        return $this->render(
            'CatMSFrontendBundle:Primary:simple-subpage.html.twig', 
            array(
                'content' => $common->getContent($slug)
            )
        );        
    }
    
    public function contactAction() 
    {
        $em = $this->getDoctrine()->getManager();
        $common = new CommonMethods($em);

        $request = $this->getRequest();
        
        if ($request->getMethod() === 'POST') {
            
            $post = $request->request->all();
            
            $message = \Swift_Message::newInstance()
                ->setContentType('text/html')
                ->setSubject('CatMS - Create Account')
                ->setFrom(array('catms.mailer@gmail.com' => 'CatMS Admin'))
                ->setTo($common->getSetting('contact-email'))
                ->setBody(
                    $this->renderView(
                        'CatMSFrontendBundle:Email:contact.html.twig',
                        array(
                            'subject' => $post['subject'],
                            'content' => $post['message'],
                            'name' => $post['name'],
                            'phone' => $post['phone'],
                            'email' => $post['email']
                        )
                    )
                );
            $this->get('mailer')->send($message);
            
            $this->get('session')->getFlashBag()->add('noticeSuccess', 'Wiadomość została wysłana poprawnie. Dziękujemy.');
            
            return $this->redirect($this->generateUrl('nzoz-contact'));
        }
        
        return $this->render(
            'CatMSFrontendBundle:Primary:contact.html.twig', 
            array(
                'content' => $common->getContent('contact')
            )
        );        
    }
}
