<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\ContactType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ContactController
 * @package App\Controller
 */
class ContactController extends AbstractController
{

    /**
     * @param Request $request
     * @param \Swift_Mailer $mailer
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/contact", name="contact")
     */
    public function contact(Request $request, \Swift_Mailer $mailer)
    {
        $contact = new Task();
        $form = $this->createForm(ContactType::class, $contact, ['attr' => ['class' => 'w-25 m-form']]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $message = (new \Swift_Message($contact->getSubject()))
                ->setFrom($contact->getEmail())
                ->setTo('usako@usako.fr')
                ->setBody(
                    '<html>' .
                    ' <body>' .
                    'Nom: ' . $contact->getName() . '<br>' .
                    'Prénom: ' . $contact->getFirstName() . '<br>' .
                    'Message: ' . nl2br($contact->getContent()) .
                    ' </body>' .
                    '</html>',
                    'text/html'
                );
            $mailer->send($message);
        }

        return $this->render('contact/contact.html.twig', [
            'form' => $form->createView()
        ]);
    }


    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/contact", name="send")
     */
    public function sendMessage()
    {
        return $this->render('contact/send.html.twig');
    }


}
