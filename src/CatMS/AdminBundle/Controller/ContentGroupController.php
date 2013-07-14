<?php

namespace CatMS\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use CatMS\AdminBundle\Entity\ContentGroup;
use CatMS\AdminBundle\Form\ContentGroupType;

/**
 * ContentGroup controller.
 *
 * @Route("/admin/content-group")
 */
class ContentGroupController extends Controller
{
    /**
     * Lists all ContentGroup entities.
     *
     * @Route("/list/{page}", 
     *  name="content-group",
     *  defaults={"page"=1}
     * )
     * @Method("GET")
     * @Template()
     */
    public function indexAction($page)
    {
        $em = $this->getDoctrine()->getManager();
        $dql   = "SELECT cg FROM CatMSAdminBundle:ContentGroup cg";
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
     * @Route("/", name="content-group-create")
     * @Method("POST")
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
            return $this->redirect($this->generateUrl('content-group-show', array('id' => $entity->getId())));
        } else {
            $this->get('session')->getFlashBag()->add('noticeError', 'create.error');
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to create a new ContentGroup entity.
     *
     * @Route("/new", name="content-group-new")
     * @Method("GET")
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
     * @Route("/{id}", name="content-group-show")
     * @Method("GET")
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
     * @Route("/{id}/edit", name="content-group-edit")
     * @Method("GET")
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
     * @Route("/{id}", name="content-group-update")
     * @Method("PUT")
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
            $this->get('session')->getFlashBag()->add('noticeError', 'update.error');
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
     * @Route("/{id}", name="content-group-delete")
     * @Method("DELETE")
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

            $em->remove($entity);
            $em->flush();
            $this->get('session')->getFlashBag()->add('noticeSuccess', 'delete.success');
        } else {
            $this->get('session')->getFlashBag()->add('noticeError', 'delete.error');
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
