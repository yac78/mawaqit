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
     * @var array 
     */
    private $emailFrom;

    public function __construct(\Swift_Mailer $mailer, $emailFrom) {
        $this->mailer = $mailer;
        $this->emailFrom = $emailFrom;
    }

    /**
     * Send email when mosque created
     */
    function mosqueCreated(Mosque $mosque, $body) {
        $message = $this->mailer->createMessage();
        $message->setSubject('Mosquée créée')
                ->setFrom([$this->emailFrom[0] => $this->emailFrom[1]])
                ->setTo([$this->emailFrom[0] => $this->emailFrom[1]])
                ->setBody($body, 'text/html');
        $this->mailer->send($message);
    }

}
