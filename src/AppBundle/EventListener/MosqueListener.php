<?php

namespace AppBundle\EventListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use AppBundle\Entity\Mosque;
use AppBundle\Service\MailService;

class MosqueListener {

    /**
     * @var MailService 
     */
    private $mailService;

    public function __construct(MailService $mailService) {
        $this->mailService = $mailService;
    }

    public function postPersist(LifecycleEventArgs $args) {
        $entity = $args->getEntity();

        if (!$entity instanceof Mosque) {
            return;
        }
        
        $this->mailService->mosqueCreated($entity);
    }

}
