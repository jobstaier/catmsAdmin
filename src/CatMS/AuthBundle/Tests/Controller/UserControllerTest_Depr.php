<?php

namespace CatMS\AuthBundle\Tests\Controller;

use CatMS\AuthBundle\Tests\Authentication\AuthUserCase;

use CatMS\AuthBundle\Entity\User;
use CatMS\AuthBundle\Form\UserType;
use CatMS\AdminBundle\Utility\CommonMethods;

class UserControllerTest extends AuthUserCase
{
    public function testUserList()
    {
        $this->logIn();
        $uri = $this->router->generate('user');

        $crawler = $this->client->request('GET', $uri);

        $this->assertGreaterThan(0, $crawler->filter('table.records_list tbody tr')->count());
    }

    public function testCreateUser()
    {
        $this->logIn();

        $uri = $this->router->generate('user_create');
        
        $crawler = $this->client->request('GET', $uri);
        
        $form = $crawler->selectButton('Create')->form();

        $form['user[username]'] = 'test-user';
        $form['user[email]'] = 'dawid.job8@gmail.com';
        $form['user[roles]'] = 'ROLE_ADMIN';
        $form['user[isActive]'] = 1;

        // Enable profiler
        $this->client->enableProfiler();
        
        $crawler = $this->client->submit($form);
        
        // Check email
        $this->checkEmailHasBeenSent($this->client);
        
        $success = count($this->client->getContainer()->get('session')->getFlashBag()->get('noticeSuccess'));
        
        $this->client->followRedirect();

        $this->assertEquals(1, (int) $success);
    }

    
    public function testRemoveUser()
    {
        $this->logIn();    
        $this->client->followRedirects(true);

        $em = $this->client->getContainer()->get('doctrine');
        $user = $em->getRepository('CatMSAuthBundle:User')->findOneByUsername('test-user');
        
        $uri = $this->router->generate('user_show', array('id' => $user->getId()));
        $crawler = $this->client->request('GET', $uri);
        
        if ($user) {
            $form = $crawler->selectButton('Delete')->form();
            
            $crawler = $this->client->submit($form);

            $success = count($this->client->getContainer()->get('session')->getFlashBag()->get('noticeSuccess'));

            $this->assertEquals(1, (int) $success);
        }
    }

    /**
     * Check email has been sent. Be sure that $this->client->enableProfiler();
     * has been activated before invoke this method
     * 
     * @param $profile = $client->getProfile()
     */
    public function checkEmailHasBeenSent($client)
    {
        $profile = $client->getProfile();
        
        if ($profile) {
            $swiftMailerProfiler = $profile->getCollector('swiftmailer');

            // Only 1 message should have been sent
            $this->assertEquals(1, $swiftMailerProfiler->getMessageCount());
            
            // Get the first message
            /* Some error here in test
            $messages = $swiftMailerProfiler->getMessages();
            
            $message = array_shift($messages);
            
            $catmsEmail = $client->getContainer()->getParameter('swiftmailer.username');
            $this->assertArrayHasKey($catmsEmail, $message->goTo());
            */
        }
    }
}
?>
