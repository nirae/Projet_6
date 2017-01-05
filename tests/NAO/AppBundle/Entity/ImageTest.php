<?php

namespace Tests\NAO\AppBundle\Entity;

use NAO\AppBundle\Entity\Image;

class ImageTest extends \PHPUnit_Framework_TestCase
{
    private $image;

    public function __construct() {

        $this->image = new Image();
    }

    public function testExtensionSetterAndGetter() {

        $this->image->setExtension('.png');
        $this->assertEquals($this->image->getExtension(), '.png');
    }

    public function testAltSetterAndGetter() {

        $this->image->setAlt('image');
        $this->assertEquals($this->image->getAlt(), 'image');
    }
}
