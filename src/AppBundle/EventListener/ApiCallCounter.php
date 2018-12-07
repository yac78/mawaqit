<?php

namespace AppBundle\EventListener;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

class ApiCallCounter
{
    /**
     * @var TokenStorage
     */
    private $tokenStorage;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(TokenStorage $tokenStorage, EntityManagerInterface $em)
    {
        $this->tokenStorage = $tokenStorage;
        $this->em = $em;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        $path = $event->getRequest()->getPathInfo();

        if (strpos($path, "/api") !== 0) {
            return;
        }

        $token = $this->tokenStorage->getToken();
        if (null !== $token) {
            $user = $token->getUser();
            $user->incrementApiCallNumber();
            $this->em->flush();
        }
    }
}
