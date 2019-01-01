<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Mosque;
use AppBundle\Form\HijriAdjustmentType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/admin/test", options={"i18n"="false"})
 */
class TestController extends Controller
{

    /**
     * @Route("")
     */
    public function testAllAction()
    {
        $var
        return new Response('ok');
    }

}