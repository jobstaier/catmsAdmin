<?php

namespace CatMS\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use CatMS\AdminBundle\Entity\Seo;
use CatMS\AdminBundle\Form\SeoType;

/**
 * Seo controller.
 *
 */
class SeoController extends Controller
{
    /**
     * Lists all Seo entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('CatMSAdminBundle:Seo')->findAll();

        return $this->render(
            'CatMSAdminBundle:Seo:index.html.twig',
            array(
                'entities' => $entities,
            )
        );
    }

    /**
     * Creates a new Seo entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity  = new Seo();
        $form = $this->createForm(new SeoType(), $entity);
        $form->bind($request);
        
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            $this->get('session')->getFlashBag()->add('noticeSuccess', 'seo.create.success');
            return $this->redirect($this->generateUrl('seo-show', array('id' => $entity->getId())));
        } else {
            $this->get('session')->getFlashBag()->add('noticeFailure', 'seo.create.error');
        }

        return $this->render(
            'CatMSAdminBundle:Seo:new.html.twig',
            array(
                'entity' => $entity,
                'form'   => $form->createView(),
            )
        );
    }

    /**
     * Displays a form to create a new Seo entity.
     *
     */
    public function newAction()
    {
        $entity = new Seo();
        $form   = $this->createForm(new SeoType(), $entity);

        return $this->render(
            'CatMSAdminBundle:Seo:new.html.twig',
            array(
                'entity' => $entity,
                'form'   => $form->createView(),
            )
        );
    }

    /**
     * Finds and displays a Seo entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('CatMSAdminBundle:Seo')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Seo entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render(
            'CatMSAdminBundle:Seo:show.html.twig',
            array(
                'entity'      => $entity,
                'delete_form' => $deleteForm->createView(),
            )
        );
    }

    /**
     * Displays a form to edit an existing Seo entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('CatMSAdminBundle:Seo')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Seo entity.');
        }

        $editForm = $this->createForm(new SeoType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render(
            'CatMSAdminBundle:Seo:edit.html.twig',
            array(
                'entity'      => $entity,
                'edit_form'   => $editForm->createView(),
                'delete_form' => $deleteForm->createView(),
            )
        );
    }

    /**
     * Edits an existing Seo entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('CatMSAdminBundle:Seo')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Seo entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new SeoType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            $this->get('session')->getFlashBag()->add('noticeSuccess', 'seo.update.success');
            return $this->redirect($this->generateUrl('seo-edit', array('id' => $id)));
        } else {
            $this->get('session')->getFlashBag()->add('noticeFailure', 'edit.error');
        }

        return $this->render(
            'CatMSAdminBundle:Seo:edit.html.twig',
            array(
                'entity'      => $entity,
                'edit_form'   => $editForm->createView(),
                'delete_form' => $deleteForm->createView(),
            )
        );
    }

    /**
     * Deletes a Seo entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('CatMSAdminBundle:Seo')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Seo entity.');
            }

            $em->remove($entity);
            $em->flush();
            $this->get('session')->getFlashBag()->add('noticeSuccess', 'seo.delete.success');
        } else {
            $this->get('session')->getFlashBag()->add('noticeSuccess', 'seo.delete.error');
        }

        return $this->redirect($this->generateUrl('seo'));
    }

    /**
     * Creates a form to delete a Seo entity by id.
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
