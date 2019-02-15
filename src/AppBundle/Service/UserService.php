<?php

namespace AppBundle\Service;

use Doctrine\ORM\Query;
use Doctrine\ORM\EntityManager;

class UserService
{

    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var array
     */
    private $emailFrom;

    /**
     * @var \Twig_Environment
     */
    private $twig;

    public function __construct(\Swift_Mailer $mailer, EntityManager $em, \Twig_Environment $twig, $emailFrom)
    {
        $this->mailer = $mailer;
        $this->em = $em;
        $this->emailFrom = $emailFrom;
        $this->twig = $twig;
    }

    /**
     * @param $data
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    function sendEmailToAllUsers($data)
    {
        $subject = $data["subject"];

        $body = $this->twig->render("email_templates/communication.html.twig", ['content' => $data["content"]]);

        $recipients = $this->em->createQueryBuilder()
            ->from("AppBundle:User", "u")
            ->select("u.email")
            ->where("u.enabled = 1")
            ->getQuery()
            ->getScalarResult();

        $message = $this->mailer->createMessage();

        $message->setSubject($subject)
            ->setFrom([$this->emailFrom[0] => $this->emailFrom[1]])
            ->setBody($body, 'text/html');

        foreach ($recipients as $recepient) {
            $message->setTo($recepient["email"]);
            $this->mailer->send($message);
        }
    }
}
