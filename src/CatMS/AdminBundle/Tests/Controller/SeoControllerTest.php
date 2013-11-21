<?php

namespace CatMS\AdminBundle\Tests\Controller;

use CatMS\AdminBundle\Tests\AuthWebTestCase;

use CatMS\AdminBundle\Entity\Seo;

/**
 * @group seo
 *
 */
class SeoControllerTest extends AuthWebTestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    public function testIndex()
    {
        $crawler = $this->client->request('GET',  $this->router->generate('seo'));

        $this->assertGreaterThan(
            0,
            $crawler->filter('html:contains("' . $this->translator->trans('seo.pagesList') . '")')->count()
        );
    }

    public function testShowCreate()
    {
        $crawler = $this->client->request('GET',  $this->router->generate('seo-new'));

        $this->assertGreaterThan(
            0,
            $crawler->filter('html:contains("' . $this->translator->trans('seo.creatingNewSeoPage') . '")')->count()
        );
    }
}
