<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Mosque;
use AppBundle\Form\HijriAdjustmentType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/admin/test")
 */
class TestController extends Controller
{

    /**
     * @Route("")
     */
    public function testAllAction()
    {
        $em = $this->getDoctrine()->getManager();
        $mosques = $em->getRepository("AppBundle:Mosque")->findAll();
        $i = 1;
        $toolsService = $this->get('app.tools_service');
        foreach ($mosques as $mosque) {
            $mosque->setCountryFullName($toolsService->getCountryNameByCode($mosque->getCountry()));
            $em->persist($mosque);
            if ($i % 100 === 0) {
                $em->flush();
            }
            $i++;
        }
        $em->flush();
        return new Response('ok');
    }

}