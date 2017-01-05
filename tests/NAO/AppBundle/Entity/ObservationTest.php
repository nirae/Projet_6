<?php

namespace Tests\NAO\AppBundle\Entity;

use NAO\AppBundle\Entity\User;
use NAO\AppBundle\Entity\Observation;
use NAO\AppBundle\Entity\Species;
use NAO\AppBundle\Entity\Image;

class ObservationTest extends \PHPUnit_Framework_TestCase
{
    private $observation;

    public function __construct() {

        $this->observation = new Observation();
    }

    public function testConstructor() {

        $this->assertEquals($this->observation->getStatus(), "En attente");
        $this->assertEquals($this->observation->getDate(), new \DateTime());
    }

    public function testSpeciesSetterAndGetter() {

        $species = new Species();

        $this->observation->setSpecies($species);
        $this->assertEquals($this->observation->getSpecies(), $species);
    }

    public function testDateSetterAndGetter() {

        $date = new \DateTime();

        $this->observation->setDate($date);
        $this->assertEquals($this->observation->getDate(), $date);
    }

    public function testLatitudeSetterAndGetter() {

        $this->observation->setLatitude('46');
        $this->assertEquals($this->observation->getLatitude(), '46');
    }

    public function testLongitudeSetterAndGetter() {

        $this->observation->setLongitude('2');
        $this->assertEquals($this->observation->getLongitude(), '2');
    }

    public function testImageSetterAndGetter() {

        $image = new Image();

        $this->observation->setImage($image);
        $this->assertEquals($this->observation->getImage(), $image);
    }

    public function testUserMessageSetterAndGetter() {

        $this->observation->setUserMessage('message');
        $this->assertEquals($this->observation->getUserMessage(), 'message');
    }

    public function testValidationMessageSetterAndGetter() {

        $this->observation->setValidationMessage('message');
        $this->assertEquals($this->observation->getValidationMessage(), 'message');
    }

    public function testStatusSetterAndGetter() {

        $this->observation->setStatus('Validée');
        $this->assertEquals($this->observation->getStatus(), 'Validée');
    }

    public function testOwnerSetterAndGetter() {

        $user = new User();

        $this->observation->setOwner($user);
        $this->assertEquals($this->observation->getOwner(), $user);
    }
}
