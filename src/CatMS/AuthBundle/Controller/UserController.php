<?php

namespace CatMS\AuthBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use CatMS\AuthBundle\Entity\User;
use CatMS\AuthBundle\Form\UserType;
use CatMS\AuthBundle\Form\ChangePasswordType;
use CatMS\AdminBundle\Utility\Gravatar;

/**
 * User controller.
 *
 */
class UserController extends Controller
{
    /**
     * Lists all User entities.
     *
     * @Template()
     */
    public function indexAction($page)
    {
        $em = $this->getDoctrine()->getManager();
        $dql   = "SELECT u FROM CatMSAuthBundle:User u";
        $query = $em->createQuery($dql);
        
        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate($query, 
            $this->get('request')->query->get('page', $page),
            $this->container->getParameter('knp_paginator.page_range')
        );
        
        $gravatar = new Gravatar;
        foreach ($pagination as $user) {
            $user->setGravatar(
                $gravatar->getGravatar($user->getEmail())
            );
        }

        return array(
            'pagination' => $pagination,
        );
    }

    /**
     * Creates a new User entity.
     *
     * @Template("CatMSAuthBundle:User:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity  = new User();
        $form = $this->createForm(new UserType(), $entity);

        $factory = $this->get('security.encoder_factory');
        $encoder = $factory->getEncoder($entity);
        $plainPassword = $this->generatePassword();
        $password = $encoder->encodePassword($plainPassword, $entity->getSalt());
        
        $entity->setPassword($password);
        $entity->setPasswordRetype($password);
        $entity->setUserHash(substr(md5(microtime()), 0, 15));
        
        $form->bind($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();
            
            $message = \Swift_Message::newInstance()
            ->setContentType('text/html')
            ->setSubject('CatMS - Create Account')
            ->setFrom(array('catms.mailer@gmail.com' => 'CatMS Admin'))
            ->setTo($entity->getEmail())
            ->setBody(
                $this->renderView(
                    'CatMSAuthBundle:Email:create-account.html.twig',
                    array('newPw' => $plainPassword, 'username' => $entity->getUsername())
                )
            );
            $this->get('mailer')->send($message);

            $this->get('session')->getFlashBag()->add('noticeSuccess', 'User has been create. Temporary password is '.$plainPassword);
            
            return $this->redirect($this->generateUrl('user_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to create a new User entity.
     *
     * @Template()
     */
    public function newAction()
    {
        $entity = new User();
        $form   = $this->createForm(new UserType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a User entity.
     *
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('CatMSAuthBundle:User')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }
        
        $gravatar = new Gravatar();
        $entity->setGravatar($gravatar->getGravatar($entity->getEmail()));

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing User entity.
     *
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('CatMSAuthBundle:User')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }

        $editForm = $this->createForm(new UserType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing User entity.
     *
     * @Template("CatMSAuthBundle:User:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('CatMSAuthBundle:User')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new UserType(), $entity);
        
        $editForm->bind($request);
        
        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            $this->get('session')->getFlashBag()->add('noticeSuccess', 'User data has been changed successfuly!');
            return $this->redirect($this->generateUrl('user_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a User entity.
     *
     * @Route("/{id}", name="user_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('CatMSAuthBundle:User')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find User entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('user'));
    }

    /**
     * Creates a form to delete a User entity by id.
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
     * Change password action
     * 
     * @Template("CatMSAuthBundle:User:edit-password.html.twig")
     */
    public function changePasswordAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('CatMSAuthBundle:User')->find($id);
        
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }
        
        $form = $this->createForm(new ChangePasswordType(), $entity);
        $entity->setNewPassword(true);
        
        if ($request->getMethod() == 'PUT') {
            $form->bind($request);

            if ($form->isValid()) {
                $factory = $this->get('security.encoder_factory');
                $encoder = $factory->getEncoder($entity);
                $passwords = $request->request->get('user_password');

                $password = $encoder->encodePassword($passwords['password'], $entity->getSalt());
                
                $entity->setPassword($password);

                $em->persist($entity);
                $em->flush();
                
                $message = \Swift_Message::newInstance()
                    ->setContentType('text/html')
                    ->setSubject('CatMS - Generate new password')
                    ->setFrom(array('catms.mailer@gmail.com' => 'CatMS Admin'))
                    ->setTo($entity->getEmail())
                    ->setBody(
                        $this->renderView(
                            'CatMSAuthBundle:Email:changed-password.html.twig',
                            array('newPw' => $passwords['password'], 'username' => $entity->getUsername())
                        )
                    )
                ;
                $this->get('mailer')->send($message);

                $this->get('session')->getFlashBag()->add('noticeSuccess', 'Password has been changed successfuly!');
                
                return $this->redirect($this->generateUrl('user_show', array('id' => $entity->getId())));
            }   
        }
        
        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    private function generatePassword()
    {
        return UserController::generateInitialPassword();
    }
    
    public static function generateInitialPassword()
    {
        return substr(md5(microtime()), 0, 5);
    }
}
