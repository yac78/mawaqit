<?php

namespace AppBundle\Service;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class RequestService
{

    /**
     * @var Request
     */
    private $request;

    public function __construct(RequestStack $requestStack)
    {
        $this->request = $requestStack->getMasterRequest();
    }

    /**
     * Raspberry local
     * @return bool
     */
    public function isLocal()
    {
        return in_array($this->request->getHost(), ['mawaqit.local', 'localhost']) && $this->request->getPort() === 80;
    }
}
