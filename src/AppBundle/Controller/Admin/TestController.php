<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Mosque;
use Doctrine\ORM\EntityManagerInterface;
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
    public function testAllAction(EntityManagerInterface $em)
    {

        ini_set("memory_limit", "4G");
        $mosques = $em->getRepository("AppBundle:Mosque")
            ->createQueryBuilder("m")
            ->distinct("m.country")
            ->getQuery()
            ->getResult();

        $r = [];
        foreach ($mosques as $mosque) {
            $timezoneName = geoip_time_zone_by_country_and_region($mosque->getCountry());
            $r[$mosque->getCountry()] = $timezoneName;
        }

        foreach ($r as $country => $timezone) {
            echo "update configuration set timezone_name = '$timezone'  where id in (select configuration_id from mosque where country = '$country');<br>";
        }

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