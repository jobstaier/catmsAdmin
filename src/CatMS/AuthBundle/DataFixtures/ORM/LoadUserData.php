<?php

namespace CatMS\AuthBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Common\Persistence\ObjectManager;
use CatMS\AuthBundle\Entity\User;
use CatMS\AuthBundle\Controller\UserController;

class LoadUserData extends AbstractFixture  implements OrderedFixtureInterface, ContainerAwareInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $userAdmin = new User();
        
        $userAdmin->setUsername('jobs');
        $userAdmin->setEmail('d.job@catdesign.pl');
        $userAdmin->setRoles('ROLE_DEVELOPER');
        
        $encoder = $this->container
                ->get('security.encoder_factory')
                ->getEncoder($userAdmin)
        ;

        $plainPassword = 'admin';
        $password = $encoder->encodePassword($plainPassword, $userAdmin->getSalt());
        
        $userAdmin->setPassword($password);
        $userAdmin->setPasswordRetype($password);
        $userAdmin->setUserHash(substr(md5(microtime()), 0, 15));

        $manager->persist($userAdmin);
        $manager->flush();
    }
    
    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 1;
    }
    
    /**
     * {@inheritDoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
}
