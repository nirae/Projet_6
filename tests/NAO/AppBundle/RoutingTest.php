<?php

namespace Tests\NAO\AppBundle;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class RoutingTest extends WebTestCase
{

    /**
    * For Anonymes
    * @dataProvider goodUrlsAnonymes
    */
    public function testPageIsSuccessfulForAnonymes($url) {

        $this->client = static::createClient();

        $this->client->request('GET', $url);

        $this->assertTrue($this->client->getResponse()->isSuccessful());
    }

    /**
    * For Anonymes
    * @dataProvider badUrlsAnonymes
    */
    public function testPageIsNotSuccessfulForAnonymes($url) {

        $this->client = static::createClient();

        $this->client->request('GET', $url);

        $this->assertFalse($this->client->getResponse()->isSuccessful());
    }

    /**
    * For Admins
    * @dataProvider badUrlsAdmin
    */
    public function testPageIsNotSuccessfulForAdmin($url) {

        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'adminpass',
        ));

        $client->request('GET', $url);

        $this->assertFalse($client->getResponse()->isSuccessful());
    }


    /**
    * For Naturs
    * @dataProvider badUrlsNaturs
    */
    public function testPageIsNotSuccessfulForNaturs($url) {

        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'natur',
            'PHP_AUTH_PW'   => 'naturpass',
        ));

        $client->request('GET', $url);

        $this->assertFalse($client->getResponse()->isSuccessful());
    }

    /**
    * For Amateurs
    * @dataProvider badUrlsAmateurs
    */
    public function testPageIsNotSuccessfulForAmateurs($url) {

        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'user',
            'PHP_AUTH_PW'   => 'userpass',
        ));

        $client->request('GET', $url);

        $this->assertFalse($client->getResponse()->isSuccessful());
    }

    // Anonymes
    public function goodUrlsAnonymes() {
        return array(
            array('/'),
            array('/login'),
            array('/register'),
        );
    }

    public function badUrlsAnonymes() {
        return array(
            array('/blablabla'),
            array('/24'),
            array('/backoffice/mes-observations'),
            array('/backoffice/ajouter'),
            array('/backoffice/validations'),
            array('/backoffice/mon-compte'),
            array('/backoffice/admin'),
            array('/backoffice/admin-ajouter'),
        );
    }

    // Amateurs
    public function badUrlsAmateurs() {
        return array(
            array('/blablabla'),
            array('/24'),
            array('/backoffice/validations'),
            array('/backoffice/admin'),
            array('/backoffice/admin-ajouter'),
        );
    }

    // Naturs
    public function badUrlsNaturs() {
        return array(
            array('/blablabla'),
            array('/24'),
            array('/backoffice/admin'),
            array('/backoffice/admin-ajouter'),
        );
    }

    // Admins
    public function badUrlsAdmin() {
        return array(
            array('/blablabla'),
            array('/24'),
        );
    }
}
