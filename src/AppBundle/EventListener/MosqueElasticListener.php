<?php

namespace AppBundle\EventListener;


use AppBundle\Entity\Configuration;
use AppBundle\Entity\Mosque;
use AppBundle\Service\MosqueService;
use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;

class MosqueElasticListener implements EventSubscriber
{
    /**
     * @var MosqueService
     */
    private $mosqueService;

    public function __construct(MosqueService $mosqueService)
    {
        $this->mosqueService = $mosqueService;
    }

    public function getSubscribedEvents()
    {
        return [
            Events::preRemove,
            Events::preUpdate,
        ];
    }

    public function preRemove(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        if (!$entity instanceof Mosque) {
            return;
        }

        $this->mosqueService->elasticDelete($entity);
    }

    public function preUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        if (!$entity instanceof Mosque || $entity instanceof Configuration) {
            return;
        }

        $mosque = $entity;

        if ($entity instanceof Configuration) {
            $entity = $entity->getMos;
        }

        $this->mosqueService->elasticCreate($entity);
    }

}
