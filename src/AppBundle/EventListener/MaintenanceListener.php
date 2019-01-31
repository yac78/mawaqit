<?php

namespace AppBundle\EventListener;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

class MaintenanceListener
{
    private $maintenanceHeader;
    private $lockFilePath;
    private $twig;

    public function __construct($lockFilePath, $maintenanceHeader, \Twig_Environment $twig)
    {
        $this->maintenanceHeader = $maintenanceHeader;
        $this->lockFilePath = $lockFilePath;
        $this->twig = $twig;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {

        if($event->getRequest()->headers->has($this->maintenanceHeader)){
            return;
        }

        if (!file_exists($this->lockFilePath)) {
            return;
        }

        $event->setResponse(
            new Response(
                $this->twig->render('maintenance.html.twig'),
                Response::HTTP_SERVICE_UNAVAILABLE
            )
        );
        $event->stopPropagation();
    }
}