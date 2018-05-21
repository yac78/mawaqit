<?php

namespace AppBundle\Twig;

use AppBundle\Service\RequestService;

class LocalExtension extends \Twig_Extension
{

    /**
     * @var RequestService
     */
    private $requestService;

    public function __construct(RequestService $requestService)
    {
        $this->requestService = $requestService;
    }

    public function getFunctions()
    {
        return [
            new \Twig_Function('isNotLocal', array($this, 'isNotLocal')),
        ];
    }

    public function isNotLocal()
    {
        return !$this->requestService->isLocal();
    }

    public function getName()
    {
        return 'isNotLocal';
    }
}