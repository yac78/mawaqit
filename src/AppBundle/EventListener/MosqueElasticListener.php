<?php

namespace AppBundle\EventListener;

use AppBundle\Entity\Configuration;
use AppBundle\Entity\Mosque;
use AppBundle\Service\MosqueService;
use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Events;

class MosqueElasticListener implements EventSubscriber
{
    /**
     * @var MosqueService
     */
    private $mosqueService;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(MosqueService $mosqueService, EntityManagerInterface $em)
    {
        $this->mosqueService = $mosqueService;
        $this->em = $em;
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

        if (!$entity instanceof Mosque && !$entity instanceof Configuration) {
            return;
        }

        if ($entity instanceof Configuration) {
            $entity = $this->em->getRepository(Mosque::class)->findOneBy([
                'configuration' => $entity
            ]);

            if (!$entity instanceof Mosque) {
                return;
            }
        }

        $this->mosqueService->elasticCreate($entity);
    }

}
