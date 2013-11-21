<?php

namespace CatMS\AdminBundle\Tests\Controller;

use CatMS\AdminBundle\Tests\AuthWebTestCase;

/**
 * @group seo
 *
 */
class FrontControllerTest extends AuthWebTestCase
{
    public function testFrontPageIndex()
    {
        $crawler = $this->client->request('GET', $this->router->generate('admin-home'));
        $this->assertEquals(
            1,
            $crawler->filter('.hero-unit h2:contains("' .
                $this->translator->trans('auth.welcome', array('username' => 'jobs')) .
                '")')->count()
        );
    }
}