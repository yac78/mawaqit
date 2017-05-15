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
        
//        die(dump(array_merge(
//                            $mosque->getConfiguration()->getFormatedConfig(), 
//                            [
//                                "site" => "Horaires de prière sur mobile et tablette <span style=\"color=> #1b6d85;\">http://falah-cachan.horaires-de-priere.fr</span>",
//                                "supportTel" => "+33629111641",
//                                "supportEmail" => "horaires-priere@binary-consulting.fr",
//                            ]
//                    )));
        
        return $this->render('mosque/mosque.html.twig', [
                    'version' => $this->getParameter('version'),
                    'config' => json_encode(array_merge(
                            $mosque->getConfiguration()->getFormatedConfig(), 
                            [
                                "site" => "Horaires de prière sur mobile et tablette <span style=\"color=> #1b6d85;\">http://falah-cachan.horaires-de-priere.fr</span>",
                                "supportTel" => "+33629111641",
                                "supportEmail" => "horaires-priere@binary-consulting.fr",
                            ]
                    ))
        ]);
    }

}
