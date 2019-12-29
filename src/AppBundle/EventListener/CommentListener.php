<?php

namespace AppBundle\EventListener;


use AppBundle\Entity\Comment;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

class CommentListener
{
    /**
     * @var TokenStorage
     */
    private $tokenStorage;

    public function __construct(TokenStorage $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        if (!$entity instanceof Comment) {
            return;
        }

        $entity->setUser($this->tokenStorage->getToken()->getUser());
    }
}
