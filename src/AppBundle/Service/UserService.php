<?php

namespace AppBundle\Service;

use AppBundle\Entity\Mosque;
use Doctrine\ORM\EntityManager;
use Swift_Mailer;
use Twig\Environment;

class UserService
{

    /**
     * @var Swift_Mailer
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
     * @var Environment
     */
    private $twig;

    public function __construct(Swift_Mailer $mailer, EntityManager $em, Environment $twig, $emailFrom)
    {
        $this->mailer = $mailer;
        $this->em = $em;
        $this->emailFrom = $emailFrom;
        $this->twig = $twig;
    }

    /**
     * @param $data
     *
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    function sendEmailToAllUsers($data)
    {
        $subject = $data["subject"];

        $body = $this->twig->render("email_templates/communication.html.twig", ['content' => $data["content"]]);

        $result = $this->em->createQueryBuilder()
            ->from("AppBundle:Mosque", "m")
            ->innerJoin("m.user", "u")
            ->select("u.email")
            ->distinct()
            ->getQuery()
            ->getScalarResult();

        $emails = array_column($result, "email");

        $message = $this->mailer->createMessage();
        $message->setSubject($subject)
            ->setTo($this->emailFrom[0])
            ->setFrom([$this->emailFrom[0] => $this->emailFrom[1]])
            ->setBody($body, 'text/html');

        $tmp = [];
        $i = 0;
        foreach ($emails as $email) {
            $i++;
            $tmp[] = $email;
            if ($i === 100) {
                $message->setBcc($tmp);
                $this->mailer->send($message);
                $i = 0;
                $tmp = [];
            }
        }

        if (!empty($tmp)) {
            $message->setBcc($tmp);
            $this->mailer->send($message);
        }
    }

    function remindUserToUploadScreenPhoto()
    {
        $mosques = $this->em->getRepository("AppBundle:Mosque")->getMosquesWithoutScreenPhoto();
        $message = $this->mailer->createMessage();
        $message->setFrom([$this->emailFrom[0] => $this->emailFrom[1]]);

        /**
         * @var Mosque $mosque
         */
        foreach ($mosques as $mosque) {
            $mosque->incrementEmailScreenPhotoReminder();
            if ($mosque->getEmailScreenPhotoReminder() <= 3) {
                $message->setSubject("Rappel / Reminder / تذكير");
                $body = $this->twig->render("email_templates/mosque_screen_photo_reminder.html.twig",
                    ['mosque' => $mosque]);
                $message->setBody($body, 'text/html');
                $message->setTo($mosque->getUser()->getEmail());
                $this->mailer->send($message);
            }

            if ($mosque->getEmailScreenPhotoReminder() > 3) {
                $mosque->suspend(Mosque::SUSPENSION_REASON_MISSING_PHOTO);
            }
        }

        $this->em->flush();
    }
}
