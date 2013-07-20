<?php

namespace CatMS\AdminBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Common\Persistence\ObjectManager;
use CatMS\AdminBundle\Entity\ContentGroup;

class LoadContentGroupData extends AbstractFixture  implements OrderedFixtureInterface, ContainerAwareInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $entity = new ContentGroup();

        $entity->setSlug('symfony2');
        $entity->setDescription('Symfony2');
        
        $manual = '<div class="alert alert-block">'.
            '<button type="button" class="close" data-dismiss="alert">&times;</button>'.
            '<h4>Tutorial</h4>'.
            'Speed up the creation and maintenance of your PHP web applications. Replace the repetitive coding tasks by power, control and pleasure..<br />'.
            '<h5>Overview</h5>Symfony is a PHP framework for web projects.'.
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
