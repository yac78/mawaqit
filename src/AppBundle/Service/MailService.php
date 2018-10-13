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
        $this->doNotReplyEmail = $doNotReplyEmail;
    }

    /**
     * Send email when mosque created
     * @param Mosque $mosque
     * @param integer $totalMosqueCount
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    function mosqueCreated(Mosque $mosque, $totalMosqueCount)
    {
        $body = $this->twig->render(self::TEMPLATE_MOSQUE_CREATED, [
            'mosque' => $mosque,
            'total' => $totalMosqueCount,
        ]);

        $message = $this->mailer->createMessage();
        $message->setSubject('Nouvelle mosquée')
            ->setFrom($this->checkEmail)
            ->setTo($this->checkEmail)
            ->setBody($body, 'text/html');
        $this->mailer->send($message);
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
        $body = $this->twig->render(self::TEMPLATE_MOSQUE_VALIDATED, [
            'mosque' => $mosque,
        ]);

        $message = $this->mailer->createMessage();
        $message->setSubject($mosque->getTitle() . " | Votre mosquée a été validée / Your mosque has been validated")
            ->setFrom($this->doNotReplyEmail)
            ->setTo($mosque->getUser()->getEmail())
            ->setBody($body, 'text/html');
        $this->mailer->send($message);
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
        $body = $this->twig->render(self::TEMPLATE_MOSQUE_SUSPENDED, [
            'mosque' => $mosque,
        ]);

        $message = $this->mailer->createMessage();
        $message->setSubject($mosque->getTitle() . " | Votre mosquée a été suspendue / Your mosque has been suspended")
            ->setFrom($this->checkEmail)
            ->setTo($mosque->getUser()->getEmail())
            ->setBody($body, 'text/html');
        $this->mailer->send($message);
    }

    /**
     * Send email to user to check information of the mosque
     * @param Mosque $mosque
     * @param boolean $duplicated mosque
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    function checkMosque(Mosque $mosque, $duplicated)
    {
        $template = $duplicated ? self::TEMPLATE_MOSQUE_DUPLICATED : self::TEMPLATE_MOSQUE_CHECK;
        $body = $this->twig->render($template, ['mosque' => $mosque]);
        $title = $mosque->getTitle() . " | " . ($duplicated ? "Votre mosquée est déjà sur Mawaqit / Your mosque is already on Mawaqit" : "Nous avons besoin d'informations / We need informations");
        $message = $this->mailer->createMessage();
        $message->setSubject($title)
            ->setFrom($this->checkEmail)
            ->setTo($mosque->getUser()->getEmail())
            ->setBody($body, 'text/html');
        $this->mailer->send($message);
    }

}
