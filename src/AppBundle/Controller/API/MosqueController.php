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
     */
    public function searchAction(Request $request)
    {
        $word = $request->query->get('word') ;
        $result =$this->get('app.mosque_service')->searchApi($word);
        return new JsonResponse($result);
    }

}
