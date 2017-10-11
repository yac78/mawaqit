<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Mosque;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class MosqueController extends Controller {

    /**
     * @Route("/mosque/{slug}/{_locale}", name="mosque_deprected", requirements={"_locale"= "en|fr|ar"})
     * @ParamConverter("mosque", options={"mapping": {"slug": "slug"}})
     */
    public function mosqueDeprectedAction(Request $request, Mosque $mosque) {
        return $this->redirectToRoute("mosque", ["slug" => $mosque->getSlug()]);
    }

    /**
     * @Route("/{slug}/{_locale}", name="mosque", requirements={"_locale"= "en|fr|ar"}, defaults={"_local"="fr"})
     * @ParamConverter("mosque", options={"mapping": {"slug": "slug"}})
     */
    public function mosqueAction(Request $request, Mosque $mosque) {

        return $this->render('mosque/mosque.html.twig', [
                    'lang' => $request->getLocale(),
                    'mosque' => $mosque,
                    'version' => $this->getParameter('version'),
                    "site" => $this->get("translator")->trans("prayer_mobile_site", [
                        "%site%" => $this->generateUrl("mosque", ["slug" => $mosque->getSlug()], UrlGenerator::ABSOLUTE_URL)
                    ]),
                    "supportTel" => $this->getParameter("supportTel"),
                    "supportEmail" => $this->getParameter("supportEmail"),
                    'config' => json_encode($mosque->getConfiguration()->getFormatedConfig())
        ]);
    }

    /**
     * @Route("/{slug}/has-been-updated/{lastUpdatedDate}", name="mosque_has_been_updated")
     * @ParamConverter("mosque", options={"mapping": {"slug": "slug"}})
     */
    public function hasUpdatedAjaxAction(Request $request, Mosque $mosque, $lastUpdatedDate) {
        $hasBeenUpdated = $this->get("app.prayer_times_service")->mosqueHasBeenUpdated($mosque, $lastUpdatedDate);
        $response = [
            "hasBeenUpdated" => $hasBeenUpdated,
            "lastUpdatedDate" => $mosque->getUpdated(),
        ];
        return new JsonResponse($response, 200);
    }

    /**
     * @Route("/{slug}/get-messages", name="get_messages")
     * @ParamConverter("mosque", options={"mapping": {"slug": "slug"}})
     */
    public function getMessagesAjaxAction(Request $request, Mosque $mosque) {
        $em = $this->getDoctrine()->getManager();
        $messages = $em->getRepository("AppBundle:Message")->getMessagesByMosque($mosque);
        return new Response($this->get("serializer")->serialize($messages, "json"), 200, [
            'content-type' => 'application/json'
        ]);
    }

    /**
     * get temperature of the mosque city
     * @Route("/{slug}/temperature", name="temperature")
     * @ParamConverter("mosque", options={"mapping": {"slug": "slug"}})
     */
    public function getTemperatureAjaxAction(Request $request, Mosque $mosque) {
        $temperatur = $this->get("app.weather_service")->getTemperature($mosque);
        return new Response($temperatur);
    }

}
