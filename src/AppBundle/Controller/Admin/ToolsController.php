<?php

namespace AppBundle\Controller\Admin;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Route("/admin/tools")
 */
class ToolsController extends Controller
{

    /**
     * @Route("/update-gps/{offset}")
     */
    public function updateGpsAction($offset)
    {
        $this->get("app.tools_service")->updateLocations($offset);
        return new JsonResponse();
    }

}