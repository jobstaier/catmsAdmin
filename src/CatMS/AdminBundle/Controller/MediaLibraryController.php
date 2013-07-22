<?php

namespace CatMS\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use CatMS\AdminBundle\Entity\ImageUpload;
use CatMS\AdminBundle\Controller\CommonMethods;
use CatMS\AdminBundle\Logger\History;
use Symfony\Component\HttpFoundation\Response;

/**
 * MediaLibrary controller.
 * 
 */
class MediaLibraryController extends Controller
{
    /**
     * Images list
     * 
     * @Template()
     */
    public function listAction($page, $slug)
    {
        $em = $this->getDoctrine()->getManager();
        
        if ($slug) {
            $dql   = "SELECT iu FROM CatMSAdminBundle:ImageUpload iu JOIN iu.imageGroup ig WHERE ig.slug = :slug ORDER BY iu.priority ASC";
            $group = $em->getRepository('CatMSAdminBundle:ImageGroup')->findOneBySlug($slug);
            $query = $em->createQuery($dql)->setParameter('slug', $slug);
            
            $history = new History($this->get('session'), $this->get('router'));
            $history->logListImageGroup($group);
        } else {
            $dql   = "SELECT iu FROM CatMSAdminBundle:ImageUpload iu";
            $group = null;
            $query = $em->createQuery($dql);

            $history = new History($this->get('session'), $this->get('router'));
            $history->logListImage();
        }
        
        $recordsPerPage = CommonMethods::castRecordsPerPage(
        $em->getRepository('CatMSAdminBundle:Setting')->findOneBySlug('asset-records-per-page'), 
        $this->container);
        
        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate($query, 
            $this->get('request')->query->get('page', $page),
            $recordsPerPage
        );
        
        foreach ($pagination->getItems() as $item) {
            $item->setDeleteForm(
                $this->createDeleteForm($item->getId())->createView()
            );
        }

        $csrf = $this->get('form.csrf_provider');
        $token = $csrf->generateCsrfToken('multipleDeleteToken');
        
        return array(
            'pagination' => $pagination,
            'group' => $group,
            'multipleDeleteToken' => $token
        );
    }
    
    
    /**
     * Upload image
     * 
     * @Template()
     */
    public function uploadAction($group)
    {
        $em = $this->getDoctrine()->getManager();
        $document = new ImageUpload();
        
        if ($group) {
            $form = $this->createFormBuilder($document)
                ->add('name')
                ->add('title')
                ->add('slug')
                ->add('priority')  
                ->add('redirect')  
                ->add('file')
                ->getForm();
            
            $groupEntity = $em->getRepository('CatMSAdminBundle:ImageGroup')->findOneBySlug($group);
            $document->setImageGroup($groupEntity);
        } else {
            $form = $this->createFormBuilder($document)
                ->add('name')
                ->add('title')  
                ->add('priority')
                ->add('redirect')    
                ->add('slug')
                ->add('imageGroup', 'entity', array(
                    'class' => 'CatMSAdminBundle:ImageGroup',
                    'property' => 'description',
                ))
                ->add('file')
                ->getForm();
            $groupEntity = null;
        }
        
        if ($this->getRequest()->isMethod('POST')) {

            $form->bind($this->getRequest());

            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                
                
                $files = $this->getRequest()->files->get('form');
                if ($files['file'] == null) {
                    $this->get('session')->getFlashBag()->add('noticeFailure', 'upload.error');
                    
                } else {
                    $em->persist($document);
                    $em->flush();

                    $this->get('session')->getFlashBag()->add('noticeSuccess', 'upload.success');
                }
                
                if ($group) {
                    return $this->redirect($this->generateUrl('media-library', array('page' => 1, 'slug' => $group)));
                } else {
                    return $this->redirect($this->generateUrl('media-library'));
                }
                
            } else {
                $this->get('session')->getFlashBag()->add('noticeFailure', 'upload.error');
            }
        }

        return array(
            'form' => $form->createView(), 
            'group' => $group,
            'groupEntity' => $groupEntity
        );
    }
    
    /**
     * Delete image
     * 
     * @param \CatMS\AdminBundle\ImageUpload $image Image Upload object
     * @param string $group Image group slug
     */
    public function deleteImageAction(Request $request, ImageUpload $image, $group)
    {  
        $form = $this->createDeleteForm($image->getId());
        $form->bind($request);
        
        if ($form->isValid()) {      
            $em = $this->getDoctrine()->getEntityManager();

            if (!$image) {
                throw $this->createNotFoundException('Unable to find Image entity.');
            };
            
            $em->remove($image);
            $em->flush();
            
            $history = new History($this->get('session'), $this->get('router'));
            $history->logDeleteImage();
            $this->get('session')->getFlashBag()->add('noticeSuccess', 'remove.success');
        } else {
            $this->get('session')->getFlashBag()->add('noticFailure', 'delete.error');
        }

        return $this->redirect($this->generateUrl(
            'media-library', array('page' => 1, 'slug' => $group)
        ));
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
    
    /**
     * Edit image
     * 
     * @param \CatMS\AdminBundle\Entity\ImageUpload $image
     * @param string $group
     * 
     */
    public function editImageAction(ImageUpload $image, $group)
    {   
        
        $em = $this->getDoctrine()->getEntityManager();
        
        if (!$image) {
            throw $this->createNotFoundException('Unable to find Image entity.');
        };
        
        $form = $this->createFormBuilder($image)
            ->add('title')  
            ->add('priority')  
            ->add('redirect')
            ->add('slug')
            ->add('file')
            ->add('imageGroup', 'entity', array(
                'class' => 'CatMSAdminBundle:ImageGroup',
                'property' => 'description',
            ))
            ->getForm();
        
        if ($this->getRequest()->isMethod('POST')) {

            $form->bind($this->getRequest());

            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                
                $em->persist($image);
                $em->flush();

                $history = new History($this->get('session'), $this->get('router'));
                $history->logEditImage($image);
                
                $this->get('session')->getFlashBag()->add('noticeSuccess', 'edit.success');
                if ($group) {
                    return $this->redirect($this->generateUrl('media-library', array('page' => 1, 'slug' => $group)));
                } else {
                    return $this->redirect($this->generateUrl('media-library'));
                }
                
            } else {
            $this->get('session')->getFlashBag()->add('noticeFailure', 'edit.error');
            }
        }
        
        $history = new History($this->get('session'), $this->get('router'));
        $history->logOpenEditImage($image);
        
        return $this->render('CatMSAdminBundle:MediaLibrary:edit.html.twig', array(
            'group' => $group,
            //'groupEntity' => $groupEntity,
            'form' => $form->createView(), 
            'image' => $image
        ));
    }
    
    private function getGroups()
    {
        $em = $this->getDoctrine()->getEntityManager();
        $objs = $em->getRepository('CatMSAdminBundle:ImageGroup')->findBy(array(), array('slug' => 'asc'));
        
        $results = array();
        
        foreach ($objs as $id => $obj) {
            $results[$obj->getId()] = $obj->getDescription(); 
        }
        return $results;
    }
    
    public function multipleDeleteImageAjaxAction($token)
    {
        $request = $this->getRequest();
        
        $csrf = $this->get('form.csrf_provider');
        $properToken = $csrf->generateCsrfToken('multipleDeleteToken');
        
        if ($token !== $properToken) {
            $data = json_encode(array('error' => 'Invalid csrf token'));
        } else {
            
            $data = $request->request->get('images');
            $removed = array();
            
            foreach ($data as $key => $id) {
                $em = $this->getDoctrine()->getEntityManager();
                $image = $em->getRepository('CatMSAdminBundle:ImageUpload')->find($id);
                
                $imageId = $image->getId();
                
                if ($image instanceof ImageUpload) {
                    $em->remove($image);
                    $em->flush();
                }
                
                $removed['images'][] = $imageId;
            }
        }
        
        return new Response(json_encode($removed), 200, array('Content-Type' => 'application/json'));
    }
  
    
    public function pluploadAction($group)
    {
        $request = $this->getRequest();

        $em = $this->getDoctrine()->getManager();
        
        if ($group) {
            $group = $em->getRepository('CatMSAdminBundle:ImageGroup')->findOneBySlug($group);
        } else {
            $group = $em->getRepository('CatMSAdminBundle:ImageGroup')->findOneBySlug('undefined');
        }
        
        $uploadedFile = $request->files->get('file');
        
        $image = new ImageUpload();
        
        $image->setFile($uploadedFile);
        $image->setImageGroup($group);
        $em->persist($image);
        $em->flush();
        
        return new Response(json_encode(array('notice' => 'success')), 200, array('Content-Type' => 'application/json'));
    }
}
?>
