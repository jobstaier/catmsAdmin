<?php

namespace CatMS\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use CatMS\AdminBundle\Entity\ContentManager;
use CatMS\AdminBundle\Form\ContentManagerType;
use CatMS\AdminBundle\Controller\CommonMethods;
use CatMS\AdminBundle\Logger\History;
use CatMS\AdminBundle\Entity\ContentArchive;
use Symfony\Component\HttpFoundation\Response;

/**
 * ContentManager controller.
 * 
 */
class ContentManagerController extends Controller
{
    /**
     * Lists all ContentManager entities.
     *
     * @Template()
     */
    public function listAction($page, $slug)
    {
        $em = $this->getDoctrine()->getManager();
        
        $dql = "SELECT cm FROM CatMSAdminBundle:ContentManager cm 
                JOIN cm.contentGroup cg WHERE cg.slug = :slug 
                ORDER BY cm.priority ASC";
        
        $group = $em->getRepository('CatMSAdminBundle:ContentGroup')
            ->findOneBySlug($slug);
        
        $query = $em->createQuery($dql)->setParameter('slug', $slug);

        $history = new History($this->get('session'), $this->get('router'));
        $history->logListContentGroup($group);

        $recordsPerPage = CommonMethods::castRecordsPerPage(
            $em->getRepository('CatMSAdminBundle:Setting')
                ->findOneBySlug('content-manager-list-records-per-page'), 
            $this->container
        );
        
        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate($query, 
            $this->get('request')->query->get('page', $page),
            $recordsPerPage
        );

        return array(
            'pagination' => $pagination,
            'group' => $group
        );
    }

    /**
     * Creates a new ContentManager entity.
     *
     * @Template("CatMSAdminBundle:ContentManager:new.html.twig")
     */
    public function createAction(Request $request, $group)
    {
        $em = $this->getDoctrine()->getManager();
        
        $entity  = new ContentManager();
        $form = $this->createForm(new ContentManagerType(), $entity);
        $form->bind($request);
        
        $contentGroup = $em->getRepository('CatMSAdminBundle:ContentGroup')
                ->findOneBySlug($group);
        
        if ($form->isValid()) {
            
            
            $em->persist($entity);
            $em->flush();

            $this->get('session')->getFlashBag()
                ->add('noticeSuccess', 'create.success');
            return $this->redirect($this->generateUrl(
                'content-manager-edit', array(
                    'id' => $entity->getId()
                )
            ));
        } else {
            $this->get('session')->getFlashBag()
                ->add('noticeError', 'create.error');
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'contentGroup' => $contentGroup
        );
    }

    /**
     * Displays a form to create a new ContentManager entity.
     *
     * @Template()
     */
    public function newAction($group)
    {
        $entity = new ContentManager();

        $em = $this->getDoctrine()->getEntityManager();
        $contentGroup = $em->getRepository('CatMSAdminBundle:ContentGroup')
            ->findOneBySlug($group);

        if (!$contentGroup) {
            throw $this->createNotFoundException(
                'Unable to find ContentGroup entity.'
            );
        };

        $entity->setContentGroup($contentGroup);
        $entity->setSlug($this->getDefaultSlug($entity));
        
        $form   = $this->createForm(new ContentManagerType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'contentGroup' => $contentGroup
        );
    }

    private function getDefaultSlug($entity)
    {
        $slug = $entity->getContentGroup()->getSlug();
        $em = $this->getDoctrine()->getManager();

        $i = 1;
        do {
            $contentSlug = $slug.'-content-'.$i;
            $i++;
            
            $alreadyUsed = $em->getRepository('CatMSAdminBundle:ContentManager')
                ->findOneBySlug($contentSlug);
            
        } while ($alreadyUsed);
        
        return $contentSlug;
    }
    
    /**
     * Finds and displays a ContentManager entity.
     *
     * @Template()
     */
    public function showAction($id, $group)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('CatMSAdminBundle:ContentManager')
            ->find($id);

        if (!$entity) {
            throw $this->createNotFoundException(
                'Unable to find ContentManager entity.'
            );
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
            'group' => $group
        );
    }

    /**
     * Displays a form to edit an existing ContentManager entity.
     *
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('CatMSAdminBundle:ContentManager')
            ->find($id);
        
        if (!$entity) {
            throw $this->createNotFoundException(
                'Unable to find ContentManager entity.'
            );
        }
        
        $archive = $em->getRepository('CatMSAdminBundle:ContentArchive')
            ->findBy(array('content' => $id), array('createdAt' => 'DESC'));
        
        $history = new History($this->get('session'), $this->get('router'));
        $history->logOpenEditContent($entity);
        
        $editForm = $this->createForm(new ContentManagerType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'archive' => $archive
        );
    }

    /**
     * Edits an existing ContentManager entity.
     *
     * @Template("CatMSAdminBundle:ContentManager:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('CatMSAdminBundle:ContentManager')
            ->find($id);

        if (!$entity) {
            throw $this->createNotFoundException(
                'Unable to find ContentManager entity.'
            );
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new ContentManagerType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $archived = $entity;
            $em->persist($entity);
            $em->flush();

            $history = new History($this->get('session'), $this->get('router'));
            $history->logEditContent($entity);
            
            $archive = new ContentArchive();
            $archive->setPopertiesFromContent($archived);
            
            $em->persist($archive);
            $em->flush();
            
            $this->get('session')->getFlashBag()
                    ->add('noticeSuccess', 'edit.success');
            return $this->redirect(
                $this->generateUrl('content-manager-edit', array('id' => $id)
            ));
        } else {
            $this->get('session')->getFlashBag()
                ->add('noticeFailure', 'edit.error');
            
            $archive = $em->getRepository('CatMSAdminBundle:ContentArchive')
                ->findBy(array('content' => $id), array('createdAt' => 'DESC'));
            
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'archive' => $archive
        );
    }

    /**
     * Deletes a ContentManager entity.
     *
     */
    public function deleteAction(Request $request, $id, $group)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('CatMSAdminBundle:ContentManager')
                    ->find($id);

            if (!$entity) {
                throw $this->createNotFoundException(
                    'Unable to find ContentManager entity.'
                );
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect(
                $this->generateUrl('content-manager-list', array(
                    'page' => 1, 
                    'slug' => $group
                ))
            );
    }

    /**
     * Creates a form to delete a ContentManager entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
    
    public function getArchiveContentAction($id)
    {
        $request = $this->getRequest();
        
        if ($request->isXmlHttpRequest()) {
            $em = $this->getDoctrine()->getManager();
            $archive = $em->getRepository('CatMSAdminBundle:ContentArchive')
                ->find($id);
        }
        
        return new Response(json_encode($archive->serialize()), 200, array('Content-Type' => 'application/json'));
    }
}
