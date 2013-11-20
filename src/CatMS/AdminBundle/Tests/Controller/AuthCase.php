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
        $this->router = $this->client->getContainer()->get('router');
        $this->translator = $this->client->getContainer()->get('translator');

        $this->logIn();
    }

    protected function logIn()
    {
        $session = $this->client->getContainer()->get('session');

        $firewall = 'secured_area';

        $em = $this->client->getContainer()->get('doctrine')->getManager();
        $user = $em->getRepository('CatMSAuthBundle:User')->findOneByUsername('jobs');

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

    public function testFronPageIndex()
    {
        $crawler = $this->client->request('GET', $this->router->generate('admin-home'));
        $this->assertEquals(
            1,
            $crawler->filter('.hero-unit h2:contains("' .
                $this->translator->trans('auth.welcome', array('username' => 'jobs')) .
                '")')->count()
        );
    }

    protected function isSuccessNotice()
    {
        return count($this->client->getContainer()->get('session')->getFlashBag()->get('noticeSuccess'));
    }
}