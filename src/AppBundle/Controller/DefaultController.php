<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $apiService = $this->get("app.api_service");
//        dump($apiService->getDynhosts());die;
        dump($apiService->getDynhost(1455659605));die;
//        dump($apiService->getLogins());die;
//        dump($apiService->createDynhost("toto6" ,"90.125.125.12"));
//        dump($apiService->createLogin("toto6", "toto66" ,"toto6666"));
//        dump($apiService->updateDynhost(1455659262, "toto886" ,"90.125.125.13"));
//        dump($apiService->deleteDynhost(1455659262));
//        dump($apiService->updateLogin("binary-consulting.fr-test10", "toto9"));
//        dump($apiService->deleteLogin("binary-consulting.fr-test10"));
        
        die;
        
        // replace this example code with whatever you need
//        return $this->render('default/index.html.twig', [
//            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
//        ]);
    }
}
