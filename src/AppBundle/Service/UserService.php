<?php

namespace AppBundle\Service;

use Doctrine\ORM\Query;
use Doctrine\ORM\EntityManager;

class UserService {

    /**
     * @var \Swift_Mailer 
     */
    private $mailer;

    /**
     * @var array 
     */
    private $emailFrom;
    
    /**
     * @var EntityManager 
     */
    private $em;

    public function __construct(\Swift_Mailer $mailer, $emailFrom, EntityManager $em) {
        $this->mailer = $mailer;
        $this->emailFrom = $emailFrom;
        $this->em = $em;
    }

    /**
     * Send email when mosque created
     */
    function sendEmailToAllUsers($data) {
        $subject = $data["subject"];
        $body = $data["content"];
        $usersEmail = $this->em->createQueryBuilder()
                ->from("AppBundle:User", "u")
                ->select("u.email")
                ->getQuery()
                ->getResult(Query::HYDRATE_SCALAR);
        
        $message = $this->mailer->createMessage();
        $message->setSubject($subject)
                ->setFrom([$this->emailFrom[0] => $this->emailFrom[1]])
                ->setTo(array_map('current',$usersEmail))
                ->setBody($body, 'text/html');
        $this->mailer->send($message);
    }

}
