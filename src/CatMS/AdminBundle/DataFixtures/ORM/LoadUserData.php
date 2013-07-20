<?php

namespace CatMS\AuthBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Common\Persistence\ObjectManager;
use CatMS\AdminBundle\Entity\ContentGroup;
use CatMS\AuthBundle\Controller\UserController;

class LoadContentGroupData extends AbstractFixture  implements OrderedFixtureInterface, ContainerAwareInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $entity = new ContentGroup();

        $entity->setSlug('sf2-internals');
        $entity->setDescription('Symfony2 Internals');
        
        $manual = '<div class="alert alert-block">'.
            '<button type="button" class="close" data-dismiss="alert">&times;</button>'.
            '<h4>Tutorial</h4>'.
            'Looks like you want to understand how Symfony2 works and how to extend it. That makes me very happy! This section is an in-depth explanation of the Symfony2 internals.<br />'.
            '<h5>Overview</h5>The Symfony2 code is made of several independent layers. Each layer is built on top of the previous one.'.
            '</div>';
        
        $entity->setManual($manual);
        
        $manager->persist($entity);
        $manager->flush();
    }
    
    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 2;
    }
    
    /**
     * {@inheritDoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
}
