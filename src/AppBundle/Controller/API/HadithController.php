<?php

namespace AppBundle\Controller\API;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/api/1.0.0/hadith")
 */
class HadithController extends Controller
{

    /**
     * @Route("/random", name="random_hadith", options={"i18n"="false"})
     * @Method("GET")
     * @param Request $request
     * @return Response
     */
    public function randomAction(Request $request)
    {
        $lang = $request->query->get('lang', 'ar');
        $maxLength = $request->query->get('maxLength', 500);
        if (!in_array($lang, ['ar', 'fr', 'both'])) {
            return new Response('The parameter lang must be fr, ar or both', Response::HTTP_BAD_REQUEST);
        }

        if ($lang === "both") {
            $languages = ['ar', 'fr'];
            $lang = $languages[array_rand($languages)];
        }

        $file = $this->getParameter("kernel.root_dir") . "/Resources/xml/ahadith-$lang.xml";
        $xmldata = simplexml_load_file($file);
        $ahadith = $xmldata->xpath('hadith');
        $hadith = $this->getRandomHadith($ahadith, $maxLength);

        $reponse = [
            "text" => preg_replace('/\s+/', ' ', preg_replace('/\n+/', '', (string)$hadith)),
            "lang" => $lang
        ];
        return new JsonResponse($reponse);
    }

    private function getRandomHadith(array $ahadith, $maxLength)
    {
        $hadith = $ahadith[array_rand($ahadith)];
        if (strlen($hadith) < 10 || strlen($hadith) > (int)$maxLength) {
            return $this->getRandomHadith($ahadith, $maxLength);
        }
        return $hadith;
    }

}
