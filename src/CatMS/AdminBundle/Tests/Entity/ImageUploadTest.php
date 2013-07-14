<?php

namespace CatMS\AdminBundle\Tests\Entity;

use CatMS\AdminBundle\Entity\ImageUpload;

class ImageUploadTest extends \PHPUnit_Framework_TestCase
{
    public function testName()
    {
        $image = new ImageUpload();
        
        $this->assertEquals('some-file-name', $image->setName('some-file-name')->getName());


    }
}
