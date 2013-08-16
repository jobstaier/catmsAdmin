<?php
namespace CatMS\AuthBundle\Listener;
 
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Doctrine\Bundle\DoctrineBundle\Registry as Doctrine;
use Symfony\Component\Security\Core\SecurityContext;
 
class LoginListener
{
    protected $doctrine;
 
    /**
     * Constructor
     * 
     * @param SecurityContext $securityContext
     * @param Doctrine        $doctrine
     */
    public function __construct(SecurityContext $securityContext, Doctrine $doctrine)
    {
        $this->securityContext = $securityContext;
        $this->doctrine = $doctrine;
    }
 
    public function onSecurityInteractiveLogin(InteractiveLoginEvent $event)
    {
        if ($this->securityContext->isGranted('IS_AUTHENTICATED_FULLY')) {
                // user has just logged in
        }

        if ($this->securityContext->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
                // user has logged in using remember_me cookie
        }

        // do some other magic here
        $user = $event->getAuthenticationToken()->getUser();

        if ($user) {
            //$user->setLastSuccessfulLogin(new \DateTime());
            //$user->setLoginFailureAttemps(0);  
            //$em = $this->doctrine->getEntityManager();
            //$em->flush();
            
            //$username = $user->getUsername();
            $request = $event->getRequest();
            $session = $request->getSession();

            $session->getFlashBag()->add('noticeSuccess', 'auth.signin.success');
        } 
    }
}
?>
