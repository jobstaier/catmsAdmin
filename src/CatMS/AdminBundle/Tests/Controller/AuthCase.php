<?php

namespace CatMS\AdminBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class AuthCase extends WebTestCase
{
    protected $client = null;

    public function setUp()
    {
        $this->client = static::createClient();
        $this->client->followRedirects();
    }

    protected function logIn()
    {
        $session = $this->client->getContainer()->get('session');

        $firewall = 'secured_area';

        $token = new UsernamePasswordToken('admin', null, $firewall, array('ROLE_ADMIN'));
        $session->set('_security_'.$firewall, serialize($token));
        $session->save();

        $cookie = new Cookie($session->getName(), $session->getId());
        $this->client->getCookieJar()->set($cookie);
    }

    public function testSecuredHello()
    {
        $this->logIn();
        $this->client->request('GET', '/admin/');
        $this->assertTrue($this->client->getResponse()->isSuccessful());
    }
}