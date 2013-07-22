<?php

namespace CatMS\AdminBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Common\Persistence\ObjectManager;
use CatMS\AdminBundle\Entity\ImageGroup;

class LoadImageGroupData extends AbstractFixture  implements OrderedFixtureInterface, ContainerAwareInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $entity = new ImageGroup();
        
        $slug = 'undefined';
        
        $entity->setSlug($slug);
        $entity->setDescription(ucfirst($slug));
        
        $manager->persist($entity);
        $manager->flush();
        
        $this->addReference('image-group-1', $entity);
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
