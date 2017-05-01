<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Mosque;

/**
 * @Route("/mosque")
 */
class MosqueController extends Controller {

    /**
     * @Route("/", name="mosque_index")
     */
    public function indexAction() {
        
        $em = $this->get("doctrine")->getEntityManager();
        $mosques = $em->getRepository("AppBundle:Mosque")->findAll();
        return $this->render('mosque/index.html.twig', [
                    "mosques" => $mosques
        ]);
    }

    /**
     * @Route("/create", name="mosque_create")
     */
    public function createAction(Request $request) {
        return $this->render('mosque/create.html.twig', []);
    }

    /**
     * @Route("/edit", name="mosque_edit")
     */
    public function editAction(Request $request) {
        return $this->render('mosque/create.html.twig', []);
    }

}
