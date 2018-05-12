<?php

namespace AppBundle\Service;

use AppBundle\Entity\Mosque;

class MailService {

    const TEMPLATE_MOSQUE_CREATED = ':mosque:email_mosque_created.html.twig';

    /**
     * @var \Swift_Mailer 
     */
    private $mailer;

    /**
     * @var string
     */
    private $email;

    public function __construct(\Swift_Mailer $mailer, $email){
        $this->mailer = $mailer;
        $this->email = $email;
    }

    /**
     * Send email when mosque created
     * @param Mosque $mosque
     * @param String $body
     */
    function mosqueCreated($body) {
        $message = $this->mailer->createMessage();
        $message->setSubject('Nouvelle mosquée ajoutée')
                ->setFrom($this->email)
                ->setTo($this->email)
                ->setBody($body, 'text/html');
        $this->mailer->send($message);
    }

}
