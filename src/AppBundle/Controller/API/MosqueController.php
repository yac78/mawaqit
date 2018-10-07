<?php

namespace AppBundle\Controller\API;

use AppBundle\Entity\Mosque;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

/**
 * @Route("/api/1.0.0/mosque")
 */

class MosqueController extends Controller
{
    /**
     * @Route("/search", options={"i18n"="false"})
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

}
