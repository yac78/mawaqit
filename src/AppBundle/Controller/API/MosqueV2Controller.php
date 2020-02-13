<?php

namespace AppBundle\Controller\API;

use AppBundle\Entity\Mosque;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * @Route("/api/2.0/mosque", options={"i18n"="false"})
 */
class MosqueV2Controller extends Controller
{
    /**
     * @Route("/search")
     * @Method("GET")
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function searchAction(Request $request)
    {
        $word = $request->query->get('word');
        $lat = $request->query->get('lat');
        $lon = $request->query->get('lon');
        $page = (int)$request->query->get('page', 1);
        $mosques = $this->get('app.mosque_service')->searchV2($word, $lat, $lon, $page);
        return new JsonResponse($mosques);
    }

    /**
     * Get pray times and other info of the mosque by uuid
     * @Route("/{uuid}/prayer-times")
     * @ParamConverter("mosque", options={"mapping": {"uuid": "uuid"}})
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

        if (!$mosque->isMosque()) {
            throw new NotFoundHttpException();
        }

        if ($request->query->has('updatedAt')) {
            $updatedAt = $request->query->get('updatedAt');
            if (!is_numeric($updatedAt)) {
                throw new BadRequestHttpException();
            }

            if ($mosque->getUpdated()->getTimestamp() <= $updatedAt) {
                return new Response(null, Response::HTTP_NOT_MODIFIED);
            }
        }

        $calendar = $request->query->has('calendar');
        $result = $this->get('app.prayer_times')->prayTimes($mosque, $calendar);
        return new JsonResponse($result);
    }

}
