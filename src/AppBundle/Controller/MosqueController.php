<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Mosque;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class MosqueController extends Controller {

    /**
     * @Route("/mosque/{slug}", name="mosque")
     * @ParamConverter("mosque", options={"mapping": {"slug": "slug"}})
     */
    public function mosqueAction(Request $request, Mosque $mosque) {
        die(dump($mosque));
        
        return $this->render('mosque/index.html.twig', [
        ]);
    }
}
