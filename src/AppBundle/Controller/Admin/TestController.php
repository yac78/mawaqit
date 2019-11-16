<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Mosque;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/admin/test", options={"i18n"="false"})
 */
class TestController extends Controller
{

    /**
     * @Route("")
     */
    public function testAction(EntityManagerInterface $em)
    {
        return new Response('ok', 200);
    }

    /**
     * @Route("/mail-preview/{template}/{id}")
     */
    public function mailPreviewAction($template, Mosque $mosque)
    {
        return $this->render(":email_templates:$template.html.twig", [
            "mosque" => $mosque,
            "content" => 'toto'
        ]);
    }

}