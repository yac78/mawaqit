<?php

namespace AppBundle\EventListener;

use AppBundle\Entity\User;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

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

        $token = $this->tokenStorage->getToken();

        if (strpos($uri, '/admin/cgu') === false
            && $token instanceOf TokenInterface
            && ($user = $token->getUser()) instanceOf User
            && $user->isTou() === false) {

            $url = $this->router->generate('terms_of_use_index');
            $response = new RedirectResponse($url);
            $event->setResponse($response);
        }
    }
}
