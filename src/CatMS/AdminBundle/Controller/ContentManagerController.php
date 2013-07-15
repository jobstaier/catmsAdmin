<?php

namespace CatMS\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use CatMS\AdminBundle\Entity\ContentManager;
use CatMS\AdminBundle\Form\ContentManagerType;
use CatMS\AdminBundle\Controller\CommonMethods;
use CatMS\AdminBundle\Logger\History;
use CatMS\AdminBundle\Entity\ContentArchive;

/**
 * ContentManager controller.
 *
 * @Route("/admin/content-manager")
 * 
 */
class ContentManagerController extends Controller
{
    /**
     * Module description page.
     * 
     * @Route("/", 
     *  name="content-manager"
     * )
     */
    public function indexAction()
    {
        return $this->render('CatMSAdminBundle:ContentManager:index.html.twig',
                array());
    }   
    
    /**
     * Lists all ContentManager entities.
     *
     * @Route("/list/{page}/{slug}", 
     *  name="content-manager-list",
     *  requirements={"page"="\d+"},
     *  defaults={"page"=1, "slug"=null}
     * )
     * @Method("GET")
     * @Template()
     */
    public function listAction($page, $slug)
    {
        $em = $this->getDoctrine()->getManager();
        
        if ($slug) {
            $dql   = "SELECT cm FROM CatMSAdminBundle:ContentManager cm JOIN cm.contentGroup cg WHERE cg.slug = :slug ORDER BY cm.priority ASC";
            $group = $em->getRepository('CatMSAdminBundle:ContentGroup')->findOneBySlug($slug);
            $query = $em->createQuery($dql)->setParameter('slug', $slug);
                    
            $history = new History($this->get('session'), $this->get('router'));
            $history->logListContentGroup($group);
        } else {
            $dql   = "SELECT cm FROM CatMSAdminBundle:ContentManager cm";
            $group = null;
            $query = $em->createQuery($dql);
        }
        
        $recordsPerPage = CommonMethods::castRecordsPerPage(
            $em->getRepository('CatMSAdminBundle:Setting')->findOneBySlug('content-manager-list-records-per-page'), 
            $this->container);
        
        
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
     * @Route("/new/create/{group}", 
     *  name="content-manager-create",
     *  defaults={"group"=null}
     * )
     * @Method("POST")
     * @Template("CatMSAdminBundle:ContentManager:new.html.twig")
     */
    public function createAction(Request $request, $group)
    {
        $entity  = new ContentManager();
        $form = $this->createForm(new ContentManagerType(), $entity);
        $form->bind($request);
        
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            
            $em->persist($entity);
            $em->flush();

            $this->get('session')->getFlashBag()->add('noticeSuccess', 'create.success');
            return $this->redirect($this->generateUrl('content-manager-show', array('id' => $entity->getId(), 'group' => $group)));
        } else {
            $this->get('session')->getFlashBag()->add('noticeError', 'create.error');
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'group' => $group
        );
    }

    /**
     * Displays a form to create a new ContentManager entity.
     *
     * @Route("/new/{group}", 
     *  name="content-manager-new",
     *  defaults={"group"=null}
     * )
     * @Method("GET")
     * @Template()
     */
    public function newAction($group)
    {
        $entity = new ContentManager();
        
        if ($group) {
            $em = $this->getDoctrine()->getEntityManager();
            $contentGroup = $em->getRepository('CatMSAdminBundle:ContentGroup')->findOneBySlug($group);
            
            if (!$contentGroup) {
                throw $this->createNotFoundException('Unable to find ContentGroup entity.');
            };
            
            $entity->setContentGroup($contentGroup);
        }
        
        $form   = $this->createForm(new ContentManagerType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'group' => $group
        );
    }

    /**
     * Finds and displays a ContentManager entity.
     *
     * @Route("/{id}/{group}", 
     *  name="content-manager-show",
     *  defaults={"group"=null}
     * )
     * @Method("GET")
     * @Template()
     */
    public function showAction($id, $group)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('CatMSAdminBundle:ContentManager')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ContentManager entity.');
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
     * @Route("/{id}/edit/{group}", 
     *  name="content-manager-edit",
     *  requirements={"id"="\d+"},
     *  defaults={"group"=null}
     *  )
     * @Method("GET")
     * @Template()
     */
    public function editAction($id, $group)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('CatMSAdminBundle:ContentManager')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ContentManager entity.');
        }

        $history = new History($this->get('session'), $this->get('router'));
        $history->logOpenEditContent($entity);
        
        $editForm = $this->createForm(new ContentManagerType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'group' => $group
        );
    }

    /**
     * Edits an existing ContentManager entity.
     *
     * @Route("/{id}/{group}", 
     *  name="content-manager-update",
     *  defaults={"group"=null}
     * )
     * @Method("PUT")
     * @Template("CatMSAdminBundle:ContentManager:edit.html.twig")
     */
    public function updateAction(Request $request, $id, $group)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('CatMSAdminBundle:ContentManager')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ContentManager entity.');
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
            
            $this->get('session')->getFlashBag()->add('noticeSuccess', 'edit.success');
            return $this->redirect($this->generateUrl('content-manager-edit', array('id' => $id, 'group' => $group)));
        } else {
            $this->get('session')->getFlashBag()->add('noticeFailure', 'edit.error');
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'group' => $group
        );
    }

    /**
     * Deletes a ContentManager entity.
     *
     * @Route("/{id}/{group}",
     *  name="content-manager-delete",
     *  defaults={"group"=null}
     * )
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id, $group)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('CatMSAdminBundle:ContentManager')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find ContentManager entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('content-manager-list', array('page' => 1, 'slug' => $group)));
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
}
