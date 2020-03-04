<?php

namespace AppBundle\Controller\API\V1;

use AppBundle\Entity\Mosque;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/api/1.0.0", options={"i18n"="false"})
 */
class MosqueController extends Controller
{
    /**
     * @Route("/mosque/search")
     * @Method("GET")
     * @param Request $request
     *
     * @return Response
     */
    public function searchAction(Request $request)
    {
        $word = $request->query->get('word');
        $lat = $request->query->get('lat');
        $lon = $request->query->get('lon');
        $page = (int)$request->query->get('page', 1);
        $mosques = $this->get('app.mosque_service')->searchV1($word, $lat, $lon, $page);
        return new JsonResponse($mosques);
    }

    /**
     * Get pray times and other info of the mosque by ID
     * @Route("/mosque/{id}/prayer-times")
     * @Cache(public=true, maxage="300", smaxage="300", expires="+300 sec")
     * @Method("GET")
     *
     * @param Request $request
     * @param Mosque  $mosque
     *
     * @return Response
     */
    public function prayTimesAction(Request $request, Mosque $mosque)
    {
        return $this->forward("AppBundle:API\V2\Mosque:prayTimes", [
            "uuid" => $mosque->getUuid(),
            "request" => $request,
        ]);
    }

}
