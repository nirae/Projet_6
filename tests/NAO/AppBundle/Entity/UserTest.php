<?php

namespace Tests\NAO\AppBundle\Entity;

use NAO\AppBundle\Entity\User;
use NAO\AppBundle\Entity\Observation;
use Doctrine\Common\Collections\ArrayCollection;

class UserTest extends \PHPUnit_Framework_TestCase
{
    private $user;

    public function __construct() {

        $this->user = new User();
    }

    public function testConstructor() {

        $this->assertEquals($this->user->getCreationDate(), new \DateTime());
        $this->assertEquals($this->user->getRoles(), array('ROLE_USER'));
        $this->assertEquals($this->user->getObservations(), new ArrayCollection());
        $this->assertFalse($this->user->isEnabled());
    }

    public function testUsernameSetterAndGetter() {

        $this->user->setUsername('Nico');
        $this->assertEquals($this->user->getUsername(), 'Nico');
    }

    public function testOldPasswordSetterAndGetter() {

        $this->user->setOldPassword('blablabla');
        $this->assertEquals($this->user->getOldPassword(), 'blablabla');
    }

    public function testPlainPasswordSetterAndGetter() {

        $this->user->setPlainPassword('blablabla');
        $this->assertEquals($this->user->getPlainPassword(), 'blablabla');
    }

    public function testPasswordSetterAndGetter() {

        $this->user->setPassword('blablabla');
        $this->assertEquals($this->user->getPassword(), 'blablabla');
    }

    public function testRoleSetterAndGetter() {

        $this->user->setRole('role');
        $this->assertEquals($this->user->getRole(), 'role');
    }

    public function testRolesSetterAndGetter() {

        $this->user->setRoles(array('ROLE_USER'));
        $this->assertEquals($this->user->getRoles(), array('ROLE_USER'));
    }

    public function testCreationDateSetterAndGetter() {

        $date = new \DateTime();
        $this->user->setCreationDate($date);
        $this->assertEquals($this->user->getCreationDate(), $date);
    }

    public function testEmailSetterAndGetter() {

        $this->user->setEmail('email@mail.fr');
        $this->assertEquals($this->user->getEmail(), 'email@mail.fr');
    }

    public function testObservations() {

        $observation1 = new Observation();
        $observation2 = new Observation();
        $observation3 = new Observation();

        $this->user->addObservation($observation1);
        $this->assertCount(1, $this->user->getObservations());
        $this->user->addObservation($observation2);
        $this->user->addObservation($observation3);
        $this->assertCount(3, $this->user->getObservations());

        $this->user->removeObservation($observation2);
        $this->assertCount(2, $this->user->getObservations());
    }

    public function testIsActiveSetterAndGetterAndFunctions() {
        // Default : false
        $this->assertFalse($this->user->isEnabled());
        $this->assertFalse($this->user->getIsActive());
        // True
        $this->user->setIsActive(true);
        $this->assertTrue($this->user->isEnabled());
        // False
        $this->user->disable();
        $this->assertFalse($this->user->isEnabled());
        // True
        $this->user->activate();
        $this->assertTrue($this->user->isEnabled());
    }
}
