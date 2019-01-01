<?php

namespace AppBundle\Controller\Admin;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/test", options={"i18n"="false"})
 */
class TestController extends Controller
{

    /**
     * @Route("")
     */
    public function testAllAction()
    {
        return new Response('ok', 500);
    }

}