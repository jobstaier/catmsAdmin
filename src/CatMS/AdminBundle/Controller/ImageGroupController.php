<?php

namespace CatMS\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use CatMS\AdminBundle\Entity\ImageGroup;
use CatMS\AdminBundle\Form\ImageGroupType;
use CatMS\AdminBundle\Utility\CommonMethods;

/**
 * ImageGroup controller.
 *
 */
class ImageGroupController extends Controller
{
    /**
     * Lists all ImageGroup entities.
     *
     * @Template()
     */
    public function indexAction($page)
    {
        $em = $this->getDoctrine()->getManager();

        $dql   = "SELECT ig FROM CatMSAdminBundle:ImageGroup ig 
            ORDER BY ig.description ASC";
        $query = $em->createQuery($dql);
        
        $paginator  = $this->get('knp_paginator');
        
        $recordsPerPage = CommonMethods::castRecordsPerPage(
            $em->getRepository('CatMSAdminBundle:Setting')
                ->findOneBySlug('image-groups-list-records-per-page'), 
            $this->container);
        
        $pagination = $paginator->paginate($query, 
            $this->get('request')->query->get('page', $page),
            $recordsPerPage
        );
        
        return array(
            'pagination' => $pagination
        );
    }

    /**
     * Creates a new ImageGroup entity.
     *
     * @Template("CatMSAdminBundle:ImageGroup:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity  = new ImageGroup();
        $form = $this->createForm(new ImageGroupType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            $this->get('session')->getFlashBag()
                ->add('noticeSuccess', 'create.success');
            return $this->redirect($this->generateUrl('image-group-show', 
                array('id' => $entity->getId())   
            ));
        } else {
            $this->get('session')->getFlashBag()
                ->add('noticeFailure', 'create.error');
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to create a new ImageGroup entity.
     *
     * @Template()
     */
    public function newAction()
    {
        $entity = new ImageGroup();
        $form   = $this->createForm(new ImageGroupType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a ImageGroup entity.
     *
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('CatMSAdminBundle:ImageGroup')
                ->find($id);

        if (!$entity) {
            throw $this->createNotFoundException(
                'Unable to find ImageGroup entity.'
            );
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing ImageGroup entity.
     *
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('CatMSAdminBundle:ImageGroup')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException(
                'Unable to find ImageGroup entity.'
            );
        }

        $editForm = $this->createForm(new ImageGroupType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing ImageGroup entity.
     *
     * @Template("CatMSAdminBundle:ImageGroup:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('CatMSAdminBundle:ImageGroup')
            ->find($id);

        if (!$entity) {
            throw $this->createNotFoundException(
                'Unable to find ImageGroup entity.'
            );
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new ImageGroupType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            
            $em->persist($entity);
            $em->flush();
            
            $this->get('session')->getFlashBag()
                ->add('noticeSuccess', 'edit.success');
            return $this->redirect($this->generateUrl('image-group-show', 
                array('id' => $id)
            ));
        } else {
            $this->get('session')->getFlashBag()
                ->add('noticeFailure', 'edit.error');
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a ImageGroup entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('CatMSAdminBundle:ImageGroup')
                ->find($id);

            if (!$entity) {
                throw $this->createNotFoundException(
                    'Unable to find ImageGroup entity.'
                );
            }

            $em->remove($entity);
            $em->flush();
            $this->get('session')->getFlashBag()
                ->add('noticeSuccess', 'delete.success');
        } else {
            $this->get('session')->getFlashBag()
                ->add('noticeFailure', 'delete.error');
        }

        return $this->redirect($this->generateUrl('image-group'));
    }

    /**
     * Creates a form to delete a ImageGroup entity by id.
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
