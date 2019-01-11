<?php

namespace AppBundle\Controller\API;

use AppBundle\Entity\Mosque;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @Route("/api/1.0.0/tools", options={"i18n"="false"})
 */

class ToolsController extends Controller
{
    /**
     * @Route("/opcache/reset")
     * @Method("DELETE")
     */
    public function opcacheResetAction()
    {
        opcache_reset();
        return new Response("Opcache successfully reset");
    }
}
