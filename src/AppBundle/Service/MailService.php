<?php

namespace AppBundle\Service;

use Swift_Mailer;
use AppBundle\Entity\Mosque;
use Symfony\Component\DependencyInjection\ContainerInterface;

class MailService {

    const TEMPLATE_MOSQUE_CREATED = ':mosque:email_mosque_created.html.twig';

    /**
     * @var Swift_Mailer 
     */
    private $mailer;
    private $templating;
    private $container;
    private $emailFrom;

    public function __construct(ContainerInterface $container, $emailFrom) {
        $this->container = $container;
        $this->emailFrom = $emailFrom;
    }

    /**
     * Send email when mosque created
     */
    function mosqueCreated(Mosque $mosque) {
        $body = $this->getBody(self::TEMPLATE_MOSQUE_CREATED, ['mosque' => $mosque]);
        $message = \Swift_Message::newInstance()
                ->setSubject('Mosquée crée')
                ->setFrom([$this->emailFrom[0] => $this->emailFrom[1]])
                ->setTo($mosque->getUser()->getEmail())
                ->setBody($body, 'text/html');
        $this->mailer = $this->container->get("mailer");
        $this->mailer->send($message);
    }

    private function getBody($template, $params) {
        $this->templating = $this->container->get("templating");
        return $this->templating->render($template, $params);
    }
}
