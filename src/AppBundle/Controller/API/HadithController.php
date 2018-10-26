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
        $file = $this->getParameter("kernel.root_dir") . "/Resources/xml/ryiad-essalihine.xml";
        $xmldata = simplexml_load_file($file);

        if ($lang === "both") {
            $hadiths = $xmldata->xpath('hadith');
        } else {
            $hadiths = $xmldata->xpath("hadith[@lang=\"$lang\"]");
        }

        $hadith = $this->getRandomHadith($hadiths, $maxLength);

        $reponse = [
            "text" => preg_replace('/\s+/', ' ', preg_replace('/\n+/', '', (string)$hadith)),
            "lang" => (string)(isset($hadith["lang"]) ? $hadith["lang"] : "NA")
        ];
        return new JsonResponse($reponse);
    }

    private function getRandomHadith(array $hadiths, $maxLength)
    {
        $hadith = $hadiths[array_rand($hadiths)];
        if (strlen($hadith) < 10 || strlen($hadith) > (int)$maxLength) {
            return $this->getRandomHadith($hadiths, $maxLength);
        }
        return $hadith;
    }

}
