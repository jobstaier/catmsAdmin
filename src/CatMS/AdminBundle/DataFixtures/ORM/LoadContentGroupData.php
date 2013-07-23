<?php

namespace CatMS\AdminBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Common\Persistence\ObjectManager;
use CatMS\AdminBundle\Entity\ContentGroup;
use CatMS\AdminBundle\Entity\ContentFields;
use CatMS\AdminBundle\Entity\ImageGroup;

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
        $entity->setContentFields($this->loadContentFields());
        $entity->addRelatedImage($this->getReference('image-group-1'));
        
        $manager->persist($entity);
        $manager->flush();
        
        $this->addReference('content-group-1', $entity);
        
        
        $entity2 = new ContentGroup();

        $entity2->setSlug('undefined');
        $entity2->setDescription('Undefined');
        $entity2->setContentFields($this->loadContentFields());
        $entity2->setIsRemovable('000');
        
        $manager->persist($entity2);
        $manager->flush();
    }
    
    private function loadContentFields()
    {
        $entity = new ContentFields();
        
        $entity->setFieldDescriptionLabel('Description');
        $entity->setFieldFullTextLabel('Full Text');
        $entity->setFieldShortTextLabel('Short Text');
        $entity->setFieldTitleLabel('Title');
        
        return $entity;
    }
    
    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 3;
    }
    
    /**
     * {@inheritDoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
}
