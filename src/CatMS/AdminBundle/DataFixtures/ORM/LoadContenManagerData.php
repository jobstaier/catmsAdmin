<?php

namespace CatMS\AdminBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Common\Persistence\ObjectManager;
use CatMS\AdminBundle\Entity\ContentGroup;
use CatMS\AdminBundle\Entity\ContentManager;

class LoadContentManagerData extends AbstractFixture  implements OrderedFixtureInterface, ContainerAwareInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $entity = new ContentManager();

        $entity->setSlug('symfony2-content-1');
        $entity->setDescription('Controller');
        
        $fullText = '<p>A controller is a PHP function you create that takes information from the HTTP request and constructs and returns an HTTP response (as a Symfony2 Response object). The response could be an HTML page, an XML document, a serialized JSON array, an image, a redirect, a 404 error or anything else you can dream up. The controller contains whatever arbitrary logic your application needs to render the content of a page.<p>'.
                    '<p>See how simple this is by looking at a Symfony2 controller in action. The following controller would render a page that simply prints Hello world!</p>'.
                    '<p>The goal of a controller is always the same: create and return a Response object. Along the way, it might read information from the request, load a database resource, send an email, or set information on the user\'s session. But in all cases, the controller will eventually return the Response object that will be delivered back to the client.</p>';
        
        $entity->setFullText($fullText);
        $entity->setTitle('Symfony2 Controller');
        $entity->setContentGroup($this->getReference('content-group-1'));
        
        $manager->persist($entity);
        $manager->flush();
        
        $entity2 = new ContentManager();
        
        $entity2->setSlug('symfony2-content-2');
        $entity2->setDescription('HTTP Cache');
        
        $fullText = '<p>The nature of rich web applications means that they\'re dynamic. No matter how efficient your application, each request will always contain more overhead than serving a static file.<p>'.
                    '<p>And for most Web applications, that\'s fine. Symfony2 is lightning fast, and unless you\'re doing some serious heavy-lifting, each request will come back quickly without putting too much stress on your server.</p>'.
                    '<p>But as your site grows, that overhead can become a problem. The processing that\'s normally performed on every request should be done only once. This is exactly what caching aims to accomplish.</p>';
        
        $entity2->setFullText($fullText);
        $entity2->setTitle('Symfony2 HTTP Cache');
        $entity2->setContentGroup($this->getReference('content-group-1'));
        
        $manager->persist($entity2);
        $manager->flush();
    }
    
    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 4;
    }
    
    /**
     * {@inheritDoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
}
