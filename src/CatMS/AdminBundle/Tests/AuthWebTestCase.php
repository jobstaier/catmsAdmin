<?php

namespace CatMS\AdminBundle\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class AuthWebTestCase extends WebTestCase
{
    protected $client;
    protected $router;
    protected $translator;
    protected $em;

    public function setUp()
    {
        $this->client = static::createClient();
        $this->router = $this->client->getContainer()->get('router');
        $this->translator = $this->client->getContainer()->get('translator');
        $this->em = $this->client->getContainer()->get('doctrine')->getManager();

        $this->logIn();
    }

    protected function logIn()
    {
        $session = $this->client->getContainer()->get('session');

        $firewall = 'secured_area';

        $user = $this->em->getRepository('CatMSAuthBundle:User')->findOneByUsername('jobs');

        $token = new UsernamePasswordToken($user, $user->getPassword(), $firewall, $user->getRoles());
        self::$kernel->getContainer()->get('security.context')->setToken($token);

        $session->set('_security_'.$firewall, serialize($token));
        $session->save();

        $cookie = new Cookie($session->getName(), $session->getId());
        $this->client->getCookieJar()->set($cookie);
    }

    public function testSecuredArea()
    {
        $crawler = $this->client->request('GET', $this->router->generate('admin-home'));
        $this->assertTrue($this->client->getResponse()->isSuccessful());
    }

    protected function isSuccessNotice()
    {
        return count($this->client->getContainer()->get('session')->getFlashBag()->get('noticeSuccess'));
    }
}