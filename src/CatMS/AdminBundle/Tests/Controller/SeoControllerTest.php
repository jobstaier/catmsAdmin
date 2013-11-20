<?php

namespace CatMS\AdminBundle\Tests\Controller;

use CatMS\AdminBundle\Tests\Controller\AuthCase;

/**
 * @group seo
 */
class SeoControllerTest extends AuthCase
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
}
