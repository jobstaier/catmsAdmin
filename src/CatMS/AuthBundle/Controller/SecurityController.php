<?php
namespace CatMS\AuthBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Security controller.
 *
 */
class SecurityController extends Controller
{
    /**
     *
     */
    public function loginAction()
    {
        $request = $this->getRequest();
        $session = $request->getSession();

        if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(
                SecurityContext::AUTHENTICATION_ERROR
            );
        } else {
            $error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
            $session->remove(SecurityContext::AUTHENTICATION_ERROR);
        }

        return $this->render(
            'CatMSAuthBundle:Security:login.html.twig',
            array(
                'last_username' => $session->get(SecurityContext::LAST_USERNAME),
                'error'         => $error,
            )
        );
    }    
    
    
    /**
     * 
     */
    public function loginCheckAction()
    {
    }
    
    /**
     * 
     */
    public function logoutAction()
    {
        
    }

    /**
     * 
     */
    public function generateNewPasswordRequestAction()
    {
        $request = $this->getRequest();
        
        if ($request->getMethod() == 'POST') {
            $email = $request->request->get('email');
            
            $em = $this->getDoctrine()->getManager();
            $emailExist = $em->getRepository('CatMSAuthBundle:User')->findEmail($email);

            if($emailExist) {
                
                $currentUser = $emailExist[0];
                
                $message = \Swift_Message::newInstance()
                    ->setContentType('text/html')
                    ->setSubject('CatMS - Generate new password')
                    ->setFrom(array('catms.mailer@gmail.com' => 'CatMS Admin'))
                    ->setTo($email)
                    ->setBody(
                        $this->renderView(
                            'CatMSAuthBundle:Email:generate-request.html.twig',
                            array('userHash' => $currentUser->getUserHash())
                        )
                    )
                ;
                $this->get('mailer')->send($message);
                
                
                $this->get('session')->getFlashbag()->add('noticeSuccess', 'Generate new password request was sent to your email address');
                return $this->redirect($this->generateUrl('admin-home'));
            } else {
                $this->get('session')->getFlashbag()->add('noticeFailure', 'Email does not exist into database');
            }
            
        }
        
        return $this->render(
            'CatMSAuthBundle:Security:generate-new-password.html.twig',
            array('error' => null)
        );
    }
    
    /**
     * 
     */
    public function generateNewPasswordAction($userHash)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('CatMSAuthBundle:User')->findBy(array('userHash' => $userHash));
        $user = $entity[0];
        
        $factory = $this->get('security.encoder_factory');
        $encoder = $factory->getEncoder($user);

        $plainPassword = $this->generatePassword();
        $password = $encoder->encodePassword($plainPassword, $user->getSalt());
        
        $user->setPassword($password);
        $user->setPasswordRetype($password);
        
        $em->persist($user);
        $em->flush();
        
        $message = \Swift_Message::newInstance()
            ->setContentType('text/html')
            ->setSubject('CatMS - Generate new password')
            ->setFrom(array('catms.mailer@gmail.com' => 'CatMS Admin'))
            ->setTo($user->getEmail())
            ->setBody(
                $this->renderView(
                    'CatMSAuthBundle:Email:generate-new-password.html.twig',
                    array('newPw' => $plainPassword, 'username' => $user->getUsername())
                )
            )
        ;
        $this->get('mailer')->send($message);
        
        $this->get('session')->getFlashbag()->add('noticeSuccess', 'Your new password is: '.$plainPassword);
        return $this->redirect($this->generateUrl('admin-home'));
    }
    
    private function generatePassword()
    {
        return substr(md5(microtime()), 0, 5);
    }
}
