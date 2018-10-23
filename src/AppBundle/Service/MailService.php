<?php

namespace AppBundle\Service;

use AppBundle\Entity\Mosque;

class MailService
{

    const TEMPLATE_MOSQUE_CREATED = ':email_templates:mosque_created.html.twig';
    const TEMPLATE_MOSQUE_VALIDATED = ':email_templates:mosque_validated.html.twig';
    const TEMPLATE_MOSQUE_CHECK = ':email_templates:mosque_check.html.twig';
    const TEMPLATE_MOSQUE_DUPLICATED = ':email_templates:mosque_duplicated.html.twig';
    const TEMPLATE_MOSQUE_SUSPENDED = ':email_templates:mosque_suspended.html.twig';

    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    /**
     * @var \Twig_Environment
     */
    private $twig;

    /**
     * @var string
     */
    private $email;

    /**
     * @var array
     */
    private $doNotReplyEmail;

    /**
     * @var array
     */
    private $checkEmail;

    public function __construct(\Swift_Mailer $mailer, \Twig_Environment $twig, $email, $doNotReplyEmail, $checkEmail)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
        $this->email = $email; // contact@mawaqit.net
        $this->checkEmail = $checkEmail; // postmaster@mawaqit.net
        $this->doNotReplyEmail = $doNotReplyEmail; // no-reply@mawaqit.net
    }

    /**
     * Send email when mosque created
     * @param Mosque $mosque
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    function mosqueCreated(Mosque $mosque)
    {
        $title = 'Nouvelle mosquée (' . $mosque->getFullCountryName() . ')';
        $body = $this->twig->render(self::TEMPLATE_MOSQUE_CREATED, ['mosque' => $mosque]);
        $this->sendEmail($title, $body, $this->checkEmail, $this->checkEmail);
    }

    /**
     * Send email when mosque has been validated by admin
     * @param Mosque $mosque
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    function mosqueValidated(Mosque $mosque)
    {
        $title = $mosque->getTitle() . " | Votre mosquée a été validée / Your mosque has been validated";
        $body = $this->twig->render(self::TEMPLATE_MOSQUE_VALIDATED, ['mosque' => $mosque]);
        $this->sendEmail($title, $body, $mosque->getUser()->getEmail(), $this->doNotReplyEmail);
    }


    /**
     * Send email when mosque has been suspended by admin
     * @param Mosque $mosque
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    function mosqueSuspended(Mosque $mosque)
    {
        $title = $mosque->getTitle() . " | Votre mosquée a été suspendue / Your mosque has been suspended";
        $body = $this->twig->render(self::TEMPLATE_MOSQUE_SUSPENDED, ['mosque' => $mosque]);
        $this->sendEmail($title, $body, $mosque->getUser()->getEmail(), $this->checkEmail);
    }

    /**
     * Send email to user to check information of the mosque
     * @param Mosque $mosque
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    function checkMosque(Mosque $mosque)
    {
        $title = $mosque->getTitle() . " | Nous avons besoin d'informations / We need informations";
        $body = $this->twig->render(self::TEMPLATE_MOSQUE_CHECK, ['mosque' => $mosque]);
        $this->sendEmail($title, $body, $mosque->getUser()->getEmail(), $this->checkEmail);
    }

    /**
     * Send email to user to check information of the mosque when duplicated
     * @param Mosque $mosque
     * @throws @see sendEmail
     */
    function duplicatedMosque(Mosque $mosque)
    {
        $title = $mosque->getTitle() . " | Votre mosquée est en double sur Mawaqit / Your mosque is duplicated on Mawaqit";
        $body = $this->twig->render(self::TEMPLATE_MOSQUE_DUPLICATED, ['mosque' => $mosque]);
        $this->sendEmail($title, $body, $mosque->getUser()->getEmail(), $this->checkEmail);
    }

    /**
     * @param $title
     * @param $body
     * @param $to
     * @param $from
     */
    private function sendEmail($title, $body, $to, $from)
    {
        $message = $this->mailer->createMessage();
        $message->setSubject($title)
            ->setFrom($from)
            ->setTo($to)
            ->setBody($body, 'text/html');
        $this->mailer->send($message);
    }

}
