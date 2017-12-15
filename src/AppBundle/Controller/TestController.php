<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Mosque;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

/**
 * @Route("/admin")
 */
class TestController extends Controller
{

    /**
     * @Security("has_role('ROLE_ADMIN')")
     * @Route("/fix-messages-position")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $mosques = $em->getRepository("AppBundle:Mosque")->findAll();
        $connection = $em->getConnection();
        foreach ($mosques as $mosque) {
            $i = 0;
            foreach ($mosque->getMessages() as $message) {
                $connection->prepare("UPDATE message set position = $i WHERE id = " . $message->getId())->execute();
                $i++;
            }
        }

        return new Response();
    }
}
