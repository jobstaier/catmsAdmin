<?php

namespace CatMS\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use CatMS\AdminBundle\Entity\ContentGroup;
use CatMS\AdminBundle\Form\ContentGroupType;
use CatMS\AdminBundle\Utility\CommonMethods;

/**
 * ContentGroup controller.
 *
 */
class ContentGroupController extends Controller
{
    /**
     * Lists all ContentGroup entities.
     *
     * @Template()
     */
    public function indexAction($page)
    {
        $em = $this->getDoctrine()->getManager();
        $dql   = "SELECT cg FROM CatMSAdminBundle:ContentGroup cg ORDER BY cg.slug ASC";
        $query = $em->createQuery($dql);
        
        $paginator  = $this->get('knp_paginator');
        
        $recordsPerPage = CommonMethods::castRecordsPerPage(
                $em->getRepository('CatMSAdminBundle:Setting')->findOneBySlug('content-groups-list-records-per-page'), 
                $this->container);
        
        $pagination = $paginator->paginate($query, 
            $this->get('request')->query->get('page', $page),
            $recordsPerPage
        );
        
        return array(
            'pagination' => $pagination,
        );
    }

    /**
     * Creates a new ContentGroup entity.
     *
     * @Template("CatMSAdminBundle:ContentGroup:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity  = new ContentGroup();
        $form = $this->createForm(new ContentGroupType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            $this->get('session')->getFlashBag()->add('noticeSuccess', 'create.success');
            return $this->redirect($this->generateUrl('content-group'));
        } else {
            $this->get('session')->getFlashBag()->add('noticeFailure', 'create.error');
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to create a new ContentGroup entity.
     *
     * @Template()
     */
    public function newAction()
    {
        $entity = new ContentGroup();
        $form   = $this->createForm(new ContentGroupType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a ContentGroup entity.
     *
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('CatMSAdminBundle:ContentGroup')->find($id);
        
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ContentGroup entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing ContentGroup entity.
     *
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('CatMSAdminBundle:ContentGroup')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ContentGroup entity.');
        }

        $editForm = $this->createForm(new ContentGroupType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing ContentGroup entity.
     *
     * @Template("CatMSAdminBundle:ContentGroup:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('CatMSAdminBundle:ContentGroup')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ContentGroup entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new ContentGroupType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            
            $em->persist($entity);
            $em->flush();

            $this->get('session')->getFlashBag()->add('noticeSuccess', 'update.success');
            return $this->redirect($this->generateUrl('content-group-edit', array('id' => $id)));
        } else {
            $this->get('session')->getFlashBag()->add('noticeFailure', 'update.error');
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a ContentGroup entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('CatMSAdminBundle:ContentGroup')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find ContentGroup entity.');
            }

            if ($entity->getIsRemovable() == '000') {
                $this->get('session')->getFlashBag()->add('noticeFailure', 'delete.error.delete.resticted');
                return $this->redirect($this->generateUrl('content-group'));
            } elseif ($entity->getIsRemovable() == '755' && !$this->get('security.context')->isGranted('ROLE_DEVELOPER')) {
                $this->get('session')->getFlashBag()->add('noticeFailure', 'delete.error.delete.developer');
                return $this->redirect($this->generateUrl('content-group'));    
            }

            $em->remove($entity);
            $em->flush();
            $this->get('session')->getFlashBag()->add('noticeSuccess', 'delete.success');
        } else {
            $this->get('session')->getFlashBag()->add('noticeFailure', 'delete.error');
        }

        return $this->redirect($this->generateUrl('content-group'));
    }

    /**
     * Creates a form to delete a ContentGroup entity by id.
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
