<?php

namespace AppBundle\EventListener;

use AppBundle\Entity\User;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

class TermsOfUse
{

    /**
     * @var TokenStorage
     */
    private $tokenStorage;

    /**
     * @var RouterInterface
     */
    private $router;

    public function __construct(TokenStorage $tokenStorage, RouterInterface $router)
    {
        $this->tokenStorage = $tokenStorage;
        $this->router = $router;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        $uri = $event->getRequest()->getRequestUri();

        $user = $this->tokenStorage->getToken()->getUser();

        if(strpos($uri, '/admin/cgu') === false && $user instanceOf User && $user->isTou() === false){
            $url = $this->router->generate('terms_of_use_index');
            $response = new RedirectResponse($url);
            $event->setResponse($response);
        }
    }
}
