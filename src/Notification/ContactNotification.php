<?php

namespace App\Notification;

use Twig\Environment;
use App\Entity\Contact;
use Swift_Mailer;
use Swift_Message;

class ContactNotification {
    
    /**
     * mailer
     *
     * @var Swift_Mailer
     */
    private $mailer;
    
    /**
     * renderer
     *
     * @var Environment
     */
    private $renderer;


    public function __construct(Swift_Mailer $mailer, Environment $renderer)
    {
        $this->mailer = $mailer;
        $this->renderer = $renderer;
    }

    public function notify(Contact $contact, $infoEmailVendor) {
        $message = (new Swift_Message('Boutique CBK : '.$contact->getArticle()->getReference()))
                    ->setFrom('noreply@cbkshop.fr')
                    ->setTo($infoEmailVendor)
                    ->setReplyTo($contact->getEmail())
                    ->setBody($this->renderer->render('emails/contact.html.twig',[
                        'contact' => $contact
                    ]), 'text/html');

        $this->mailer->send($message);
    }
}