<?php

namespace CatMS\AdminBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Common\Persistence\ObjectManager;
use CatMS\AdminBundle\Entity\Setting;

class LoadSettingsData extends AbstractFixture  implements OrderedFixtureInterface, ContainerAwareInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $entity = new Setting();

        $entity->setSlug('asset-records-per-page');
        $entity->setDescription('Records per assets list page');
        $entity->setValue(12);
        $entity->setRange('panel');
        $entity->setFieldType('text');
        
        $manager->persist($entity);
        $manager->flush();
        
        
        $entity2 = new Setting();
        
        $entity2->setSlug('content-groups-list-records-per-page');
        $entity2->setDescription('Records per content group list page');
        $entity2->setValue(8);
        $entity2->setRange('panel');
        $entity2->setFieldType('text');
        
        $manager->persist($entity2);
        $manager->flush();
        
        
        $entity3 = new Setting();
        
        $entity3->setSlug('settings-panel-list-records-per-page');
        $entity3->setDescription('Records per settings page');
        $entity3->setValue(8);
        $entity3->setRange('panel');
        $entity3->setFieldType('text');
        
        $manager->persist($entity3);
        $manager->flush();
        
        
        $entity4 = new Setting();
        
        $entity4->setSlug('content-manager-list-records-per-page');
        $entity4->setDescription('Records per content list page');
        $entity4->setValue(8);
        $entity4->setRange('panel');
        $entity4->setFieldType('text');
        
        $manager->persist($entity4);
        $manager->flush();
        
        
        $entity5 = new Setting();
        
        $entity5->setSlug('image-groups-list-records-per-page');
        $entity5->setDescription('Records per asset groups list page');
        $entity5->setValue(8);
        $entity5->setRange('panel');
        $entity5->setFieldType('text');
        
        $manager->persist($entity5);
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
