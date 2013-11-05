<?php

namespace CatMS\FrontendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

class PrimaryController extends Controller
{
    public function indexAction()
    {
        return $this->render(
            'CatMSFrontendBundle:Primary:index.html.twig', 
            array(

            )
        );
    }

    public function sendMailAction()
    {
        $request = $this->getRequest();
        $sendFormError = false;

        if ($request->getMethod() == 'POST') {

            $vars = $request->request->all();
            foreach ($vars as $key => $value) {
                if ($value == '') {
                    $sendFormError = true;
                }
            }

            if (!$sendFormError) {
                $common = $this->get('common_methods');
                $mailTo = $common->getSetting('contact-mailer-address');
                $message = \Swift_Message::newInstance()
                    ->setContentType('text/html')
                    ->setSubject('Wiadomość ze strony Cary.pl')
                    ->setFrom(array('catms.mail@gmail.com' => 'Cary.pl - formularz kontaktowy'))
                    ->setTo($mailTo)
                    ->setBody(
                        $this->renderView(
                            'CatMSFrontendBundle:Email:contact.html.twig',
                            array('vars' => $vars)
                        )
                    )
                ;
                $this->get('mailer')->send($message);

                return new JsonResponse(
                    json_encode(array(
                        'data' => $vars,
                        'status' => true
                    ))
                );
            }
        }

        return new JsonResponse(
            json_encode(array(
                'status' => false
            ))
        );
    }
}
