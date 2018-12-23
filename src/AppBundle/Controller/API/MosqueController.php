<?php

namespace AppBundle\Controller\API;

use AppBundle\Entity\Mosque;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @Route("/api/1.0.0/mosque", options={"i18n"="false"})
 */

class MosqueController extends Controller
{
    /**
     * @Route("/search")
     * @Method("GET")
     * @param Request $request
     * @return JsonResponse
     * @throws \Doctrine\DBAL\DBALException
     */
    public function searchAction(Request $request)
    {
        $word = $request->query->get('word') ;
        $lat = $request->query->get('lat') ;
        $lon = $request->query->get('lon') ;
        $result =$this->get('app.mosque_service')->searchApi($word, $lat, $lon);
        return new JsonResponse($result);
    }


    /**
     * Get pray times and other info of the mosque by ID
     * @Route("/{mosque}/prayer-times")
     * @Method("GET")
     * @param Request $request
     * @param Mosque $mosque
     * @return JsonResponse
     */
    public function prayTimesAction(Request $request, Mosque $mosque)
    {
        if($mosque->isHome()){
            throw new NotFoundHttpException();
        }

        $calendar = $request->query->has('calendar');
        $result =$this->get('app.prayer_times')->prayTimes($mosque, $calendar);
        return new JsonResponse($result);
    }

}
