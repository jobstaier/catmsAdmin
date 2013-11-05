<?php

namespace CatMS\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use CatMS\AdminBundle\Entity\ImageUpload;
use CatMS\AdminBundle\Utility\CommonMethods;
use CatMS\AdminBundle\Logger\History;
use Symfony\Component\HttpFoundation\Response;
use CatMS\AdminBundle\Entity\ImageGroup;
use CatMS\AdminBundle\Form\AssetProtoType;

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
            $dql   = "SELECT iu FROM CatMSAdminBundle:ImageUpload iu 
                JOIN iu.imageGroup ig WHERE ig.slug = :slug 
                ORDER BY iu.priority ASC";
            $group = $em->getRepository('CatMSAdminBundle:ImageGroup')
                ->findOneBySlug($slug);
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
        $em->getRepository('CatMSAdminBundle:Setting')
            ->findOneBySlug('asset-records-per-page'), 
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
            
            $groupEntity = $em->getRepository('CatMSAdminBundle:ImageGroup')
                ->findOneBySlug($group);
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
            
            $formFile = $this->getRequest()->files->get('form');
            if ($form->isValid() && is_object($formFile['file'])) {
                $em = $this->getDoctrine()->getManager();
                
                $files = $this->getRequest()->files->get('form');
                if ($files['file'] == null) {
                    $this->get('session')->getFlashBag()
                        ->add('noticeFailure', 'upload.error');
                    
                } else {
                    $em->persist($document);
                    $em->flush();

                    $this->get('session')->getFlashBag()
                        ->add('noticeSuccess', 'upload.success');
                }

                if ($group) {
                    return $this->redirect(
                        $this->generateUrl('media-library-image-edit', 
                            array(
                                'id' => $document->getId(), 
                                'group' => $document->getImageGroup()->getSlug()
                            )
                        ));
                } else {
                    return $this->redirect($this->generateUrl('media-library-image-edit',
                        array('id' =>  $document->getId())
                    ));
                }
                
            } else {
                $this->get('session')->getFlashBag()
                    ->add('noticeFailure', 'upload.error');
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
            $em = $this->getDoctrine()->getManager();

            if (!$image) {
                throw $this->createNotFoundException(
                    'Unable to find Image entity.'
                );
            };
            
            $em->remove($image);
            $em->flush();
            
            $history = new History($this->get('session'), $this->get('router'));
            $history->logDeleteImage();
            $this->get('session')->getFlashBag()
                ->add('noticeSuccess', 'remove.success');
        } else {
            $this->get('session')->getFlashBag()
                ->add('noticeFailure', 'delete.error');
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
        $em = $this->getDoctrine()->getManager();
        
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

                $history = new History($this->get('session'), 
                    $this->get('router')
                );
                $history->logEditImage($image);
                
                $this->get('session')->getFlashBag()
                    ->add('noticeSuccess', 'edit.success');
                
                if ($group) {
                    return $this->redirect(
                        $this->generateUrl('media-library-image-edit', 
                            array(
                                'id' => $image->getId(), 'group' => $group
                            )
                        )
                    );
                } else {
                    return $this->redirect(
                        $this->generateUrl('media-library-image-edit',  
                            array(
                                'id' => $image->getId()
                            )
                        )
                    );
                }
                
            } else {
            $this->get('session')->getFlashBag()
                ->add('noticeFailure', 'edit.error');
            }
        }
        
        $history = new History($this->get('session'), $this->get('router'));
        $history->logOpenEditImage($image);
        
        return $this->render('CatMSAdminBundle:MediaLibrary:edit.html.twig', 
            array(
                'group' => $group,
                'form' => $form->createView(), 
                'image' => $image,
                'delete_form' => $this->createDeleteForm($image->getId())
                    ->createView()
            )
        );
    }
    
    private function getGroups()
    {
        $em = $this->getDoctrine()->getManager();
        $objs = $em->getRepository('CatMSAdminBundle:ImageGroup')
            ->findBy(array(), array('slug' => 'asc'));
        
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
                $em = $this->getDoctrine()->getManager();
                $image = $em->getRepository('CatMSAdminBundle:ImageUpload')
                    ->find($id);
                
                $imageId = $image->getId();
                
                if ($image instanceof ImageUpload) {
                    $em->remove($image);
                    $em->flush();
                }
                
                $removed['images'][] = $imageId;
            }
        }
        
        return new Response(json_encode($removed), 200, 
            array('Content-Type' => 'application/json')
        );
    }
  
    
    public function pluploadAction($group)
    {
        $request = $this->getRequest();

        $em = $this->getDoctrine()->getManager();
        
        if ($group) {
            $group = $em->getRepository('CatMSAdminBundle:ImageGroup')
                ->findOneBySlug($group);
        } else {
            $group = $em->getRepository('CatMSAdminBundle:ImageGroup')
                ->findOneBySlug('undefined');
        }
        
        $uploadedFile = $request->files->get('file');
        
        $image = new ImageUpload();
        
        $image->setFile($uploadedFile);
        $image->setImageGroup($group);
        $em->persist($image);
        $em->flush();
        
        return new Response(json_encode(array('notice' => 'success')), 200, 
            array('Content-Type' => 'application/json')
        );
    }
    
    public function listGridAction()
    {
        return $this->render('CatMSAdminBundle:MediaLibrary:list-grid.html.twig', 
            array()
        );
    }
    
    public function listGroupGridAction(ImageGroup $group)
    {
        return $this->render('CatMSAdminBundle:MediaLibrary:list-group-grid.html.twig', 
            array('group' => $group)
        );
    }
    
    public function editInlineImageAction()
    {
        $request = $this->getRequest();
        
        $post = $request->request->all();
        
        $em = $this->getDoctrine()->getManager();
        
        $image = $em->getRepository('CatMSAdminBundle:ImageUpload')
            ->find($post['asset_form']['id']);

        if (!$image) {
            return new Response(
                json_encode(
                    array('notice' => 'Unable to find Image entity.')
                ), 
                200, 
                array('Content-Type' => 'application/json')
            );
        };
        
        $form = $this->createForm(new AssetProtoType(), $image);
        if ($request->isMethod('POST')) {
            
            $form->bind($request);

            if ($form->isValid()) {
                
                $em->persist($image);
                $em->flush();  
                
                return new Response(
                    json_encode(
                        array(
                            'result' => 'success',
                        )
                    ), 
                    200, 
                    array('Content-Type' => 'application/json')
                );
            } else {
                return new Response(
                    json_encode(
                        array(
                            'result' => 'error',
                            'errors' => $this->getErrorMessages($form)
                        )
                    ), 
                    200, 
                    array('Content-Type' => 'application/json')
                );
            }
        }
    }
    
    private function getErrorMessages(\Symfony\Component\Form\Form $form) {      
        $errors = array();

        if ($form->hasChildren()) {
            foreach ($form->getChildren() as $child) {
                if (!$child->isValid()) {
                    $errors[$child->getName()] = $this->getErrorMessages($child);
                }
            }
        } else {
            foreach ($form->getErrors() as $key => $error) {
                $errors[] = $error->getMessage();
            }   
        }

        return $errors;
    }
    
    public function regenerateEditInlineFormImageAction()
    {
        $request = $this->getRequest();
        
        $em = $this->getDoctrine()->getManager();
        $image = $em->getRepository('CatMSAdminBundle:ImageUpload')
            ->find($request->query->get('id'));
        
        if (!$image) {
            return new Response(
                json_encode(
                    array('notice' => 'Unable to find Image entity.')
                ), 
                200, 
                array('Content-Type' => 'application/json')
            );
        };     
        
        $form = $this->createForm(new AssetProtoType(), $image)
            ->createView();
        
        return new Response(
            json_encode(
                array(
                    'editFormPrototype' => $this->renderView(
                        'CatMSAdminBundle:MediaLibrary:prototypes\assetEditPrototype.html.twig',
                         array('form' => $form)
                    ) 
                )
            ), 
            200, 
            array('Content-Type' => 'application/json')
        );
    }
    
    public function deleteAssetInlineAction(ImageUpload $image)
    {
        $em = $this->getDoctrine()->getManager();  
        
        $em->remove($image);
        $em->flush();
        
        return new Response(
            json_encode(
                array(
                    'result' => 'success',
                )
            ), 
            200, 
            array('Content-Type' => 'application/json')
        );     
    }
}

