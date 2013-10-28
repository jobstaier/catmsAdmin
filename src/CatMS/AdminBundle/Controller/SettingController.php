<?php

namespace CatMS\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use CatMS\AdminBundle\Entity\Setting;
use CatMS\AdminBundle\Form\SettingType;
use CatMS\AdminBundle\Utility\CommonMethods;

/**
 * Setting controller.
 *
 * @Route("/admin/settings")
 */
class SettingController extends Controller
{
        
    /**
     * Clear cache.
     *
     * @Route("/clear-cache",
     *  name="clear-cache"
     * )
     * @Method("GET")
     */
    public function cacheClearAction()
    {
        $cache_dir = $this->container->get('kernel')->getRootdir().'/cache';

        echo "<b>cache_dir : $cache_dir</b>";
        
        if (is_dir($cache_dir)) {
            if (basename($cache_dir) == "cache") {
                    echo "<br/><br/><b>clearing cache :</b>";
                    $this->cc($cache_dir, "dev");
                    $this->cc($cache_dir, "prod");
                    $this->cc($cache_dir, "test");
                    echo "<br/><br/><b>done !</b>";
            }
            else {
                    die("<br/> Error : cache_dir not named cache ?");
            }
        }
        else {
                die("<br/> Error : cache_dir is not a dir");
        }

        return $this->redirect($this->generateUrl('admin-home'));
    }
        
    private function rrmdir($dir) {
        if (is_dir($dir)) {
            $objects = scandir($dir);
            foreach ($objects as $object) {
                if ($object != "." && $object != "..") {
                    $o = $dir . "/" . $object;
                    if (filetype($o) == "dir") {
                            $this->rrmdir($dir."/".$object);
                    }
                    else {
                        echo "<br/>" . $o;
                        if (file_exists($o)) {
                                unlink($o);
                        }
                    }
                }
            }

            reset($objects);
            rmdir($dir);
        }
    }
 
    private function cc($cache_dir, $name) {
        $d = $cache_dir . '/' . $name;
        if (is_dir($d)) {
            echo "<br/><br/><b>clearing " . $name . ' :</b>';
            $this->rrmdir($d);
        }
    }


    /**
     * Lists all Panel Setting entities.
     *
     * @Route("/panel/{page}",
     *  name="panel-settings",
     *  requirements={"page"="\d+"},
     *  defaults={"page"=1}
     * )
     * @Method("GET")
     * @Template()
     */
    public function panelSettingAction($page)
    {
        $em = $this->getDoctrine()->getManager();
        $dql   = "SELECT s FROM CatMSAdminBundle:Setting s WHERE s.range = :range";
        $query = $em->createQuery($dql);
        $query->setParameter('range', 'Panel');

        $recordsPerPage = CommonMethods::castRecordsPerPage(
        $em->getRepository('CatMSAdminBundle:Setting')
            ->findOneBySlug('settings-panel-list-records-per-page'), 
        $this->container);
        
        $paginator  = $this->get('knp_paginator');
  
        $pagination = $paginator->paginate($query, 
            $this->get('request')->query->get('page', $page),
            $recordsPerPage
        );
        
        $this->setInlineEditForm($pagination->getItems());

        return $this->render('CatMSAdminBundle:Setting:list.html.twig', array(
            'pagination' => $pagination,
            'module' => 'Panel',
            'currentPage' => $page
        ));
    }
    
    
    /**
     * Lists all Frontend Setting entities.
     *
     * @Route("/frontend/{page}",
     *  name="frontend-settings",
     *  requirements={"page"="\d+"},
     *  defaults={"page"=1}
     * )
     * @Method("GET")
     * @Template()
     */
    public function frontendSettingAction($page)
    {
        $em = $this->getDoctrine()->getManager();
        $dql   = "SELECT s FROM CatMSAdminBundle:Setting s WHERE s.range = :range";
        $query = $em->createQuery($dql);
        $query->setParameter('range', 'Frontend');

        $recordsPerPage = CommonMethods::castRecordsPerPage(
        $em->getRepository('CatMSAdminBundle:Setting')
            ->findOneBySlug('settings-panel-list-records-per-page'), 
        $this->container);        
        
        $paginator  = $this->get('knp_paginator');
       
        $pagination = $paginator->paginate($query, 
            $this->get('request')->query->get('page', $page),
            $recordsPerPage
        );

        $this->setInlineEditForm($pagination->getItems());
        
        return $this->render('CatMSAdminBundle:Setting:list.html.twig', array(
            'pagination' => $pagination,
            'module' => 'Frontend',
            'currentPage' => $page
        ));
    }

    /**
     * Creates a new Setting entity.
     *
     * @Route("/{range}/create", name="settings-create")
     * @Method("POST")
     * @Template("CatMSAdminBundle:Setting:new.html.twig")
     */
    public function createAction(Request $request, $range)
    {
        $entity  = new Setting();
        $entity->setRange($range);
        
        $form = $this->createForm(new SettingType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            $pathname = $this->checkRange($range);
            
            $this->get('session')->getFlashBag()->add('noticeSuccess', 'create.success');
            return $this->redirect($this->generateUrl($pathname));
        } else {
            $this->get('session')->getFlashBag()->add('noticeFailure', 'create.error');
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'module' => ucfirst($range)
        );
    }

    /**
     * Displays a form to create a new Setting entity.
     *
     * @Route("/{range}/new", name="settings-new")
     * @Method("GET")
     * @Template()
     */
    public function newAction($range)
    {
        $entity = new Setting();
        $form   = $this->createForm(new SettingType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'module' => ucfirst($range)
        );
    }

    /**
     * Finds and displays a Setting entity.
     *
     * @Route("/{range}/show/{id}", name="settings-show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($range, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('CatMSAdminBundle:Setting')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Setting entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
            'module' => ucfirst($range)
        );
    }

    /**
     * Displays a form to edit an existing Setting entity.
     *
     * @Route("/{range}/edit/{id}", name="settings-edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($range, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('CatMSAdminBundle:Setting')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Setting entity.');
        }

        $entity = $this->checkAndCastBooleanFormField($entity);
        
        $editForm = $this->createForm(new SettingType(), $entity, array('form_value_field_type' => $entity->getFieldType()));
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'module' => ucfirst($range)
        );
    }

    /**
     * Edits an existing Setting entity.
     *
     * @Route("/{range}/update/{id}/{referencePage}",
     *  name="settings-update",
     *  defaults={"referencePage"=null}
     * )
     * @Method("PUT")
     * @Template("CatMSAdminBundle:Setting:edit.html.twig")
     */
    public function updateAction(Request $request, $range, $id, $referencePage)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('CatMSAdminBundle:Setting')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Setting entity.');
        }

        $entity = $this->checkAndCastBooleanFormField($entity);
        
        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new SettingType(), $entity, array('form_value_field_type' => $entity->getFieldType()));
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            $this->get('session')->getFlashBag()->add('noticeSuccess', 'edit.success');
            if (null !== $referencePage) {
                
                $pathname = $this->checkRange($range);

                return $this->redirect($this->generateUrl($pathname, array('page' => $referencePage)));
                
            } else {
                return $this->redirect($this->generateUrl('settings-edit', array('id' => $id, 'range' => $range)));
            }
        } else {
            $this->get('session')->getFlashBag()->add('noticeFailure', 'edit.error');
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'module'      => ucfirst($range)
        );
    }

    /**
     * Deletes a Setting entity.
     *
     * @Route("/{range}/delete/{id}", name="settings-delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $range, $id)
    {
        if ($range == 'panel') {
            $pathname = 'panel-settings';
        } elseif ($range == 'frontend') {
            $pathname = 'frontend-settings';
        }
        
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('CatMSAdminBundle:Setting')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Setting entity.');
            }

            $em->remove($entity);
            $em->flush();
            $this->get('session')->getFlashBag()->add('noticeSuccess', 'setting.remove.success');
        } else {
            $this->get('session')->getFlashBag()->add('noticeFailure', 'setting.remove.error');
        }

        return $this->redirect($this->generateUrl($pathname));
    }

    /**
     * Creates a form to delete a Setting entity by id.
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
    
    private function setInlineEditForm($entities)
    {        
        foreach ($entities as $key => &$obj) {
            $this->checkAndCastBooleanFormField($obj);
            $editForm = $this->createForm(new SettingType(), $obj, array('form_value_field_type' => $obj->getFieldType()));
            $obj->setInlineEditForm($editForm->createView());
            unset($editForm);
        }
        return $entities;
    }
    
    private function checkAndCastBooleanFormField($obj) {        
        $fieldValueFalse = array('false', 'null', 0, '');
        
        if ($obj->getFieldType() == 'checkbox') {
            if (in_array(strtolower($obj->getValue()), $fieldValueFalse)) {
                $obj->setValue(false);
            } else {
                $obj->setValue(true);
            }
        }
        return $obj;
    }
    
    private function checkRange($range)
    {
        if ($range == 'panel') {
            return 'panel-settings';
        } elseif ($range == 'frontend') {
            return 'frontend-settings';
        }
    }
}