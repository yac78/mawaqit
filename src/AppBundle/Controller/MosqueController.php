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

        return $this->render('mosque/mosque.html.twig', [
                    'header' => $mosque->getHeader(),
                    'footer' => $mosque->getFooterText(),
                    'version' => $this->getParameter('version'),
                    "site" => "Horaires de prière sur mobile et tablette <a href='http://falah-cachan.horaires-de-priere.fr'>http://falah-cachan.horaires-de-priere.fr</a>",
                    "supportTel" => "+33629111641",
                    "supportEmail" => "horaires-priere@binary-consulting.fr",
                    'config' => json_encode($mosque->getConfiguration()->getFormatedConfig())
        ]);
    }

}
