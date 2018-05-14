<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Mosque;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class MosqueController extends Controller
{

    /**
     * @deprecated
     * @Route("/mosque/{slug}/{_locale}", options={"i18n"="false"}, requirements={"_locale"= "en|fr|ar|ph"})
     * @ParamConverter("mosque", options={"mapping": {"slug": "slug"}})
     */
    public function mosqueDeprected1Action(Mosque $mosque)
    {
        return $this->forward("AppBundle:Mosque:mosque", ["slug" => $mosque->getSlug()]);
    }

    /**
     * @deprecated
     * @Route("/{slug}/{_locale}", options={"i18n"="false"}, requirements={"_locale"= "en|fr|ar|ph"})
     * @ParamConverter("mosque", options={"mapping": {"slug": "slug"}})
     */
    public function mosqueDeprected2Action(Mosque $mosque)
    {
        return $this->forward("AppBundle:Mosque:mosque", ["slug" => $mosque->getSlug()]);
    }

    /**
     * @Route("/{slug}", name="mosque")
     * @ParamConverter("mosque", options={"mapping": {"slug": "slug"}})
     */
    public function mosqueAction(Request $request, Mosque $mosque)
    {

        if(!$mosque->isValidated()){
            throw new NotFoundHttpException();
        }

        $mobileDetect = $this->get('mobile_detect.mobile_detector');
        $view = $request->query->get("view");
        $template = 'mosque';
        $messages = [];

        if (($view !== "desktop" && $mobileDetect->isMobile() && !$mobileDetect->isTablet()) || $view === "mobile") {
            $template .= '_mobile';
            $em = $this->getDoctrine()->getManager();
            $messages = $em->getRepository("AppBundle:Message")->getMessagesByMosque($mosque, true);
        }

        return $this->render("mosque/$template.html.twig", [
            'mosque' => $mosque,
            'version' => $this->getParameter('version'),
            "supportEmail" => $this->getParameter("supportEmail"),
            'config' => $this->get('serializer')->serialize($mosque->getConfiguration(), 'json'),
            'messages' => $messages
        ]);
    }

    /**
     * @Route("/{slug}/has-been-updated/{lastUpdatedDate}", name="mosque_has_been_updated_deprecated", options={"i18n"="false"})
     * @ParamConverter("mosque", options={"mapping": {"slug": "slug"}})
     */
    public function hasUpdatedAjaxDeprecatedAction(Request $request, Mosque $mosque, $lastUpdatedDate)
    {
        return $this->forward("AppBundle:Mosque:hasUpdatedAjax", [
            "slug" => $mosque->getSlug(),
            "request" => $request
        ]);
    }

    /**
     * @Route("/{slug}/has-been-updated", name="mosque_has_been_updated", options={"i18n"="false"})
     * @ParamConverter("mosque", options={"mapping": {"slug": "slug"}})
     */
    public function hasUpdatedAjaxAction(Request $request, Mosque $mosque)
    {
        $lastUpdatedDate = $request->query->get("lastUpdatedDate");
        if (empty($lastUpdatedDate)) {
            throw new \RuntimeException();
        }

        $hasBeenUpdated = $this->get("app.prayer_times_service")->mosqueHasBeenUpdated($mosque, $lastUpdatedDate);
        return new JsonResponse(["hasBeenUpdated" => $hasBeenUpdated]);
    }

    /**
     * get temperature of the mosque city
     * @Route("/{slug}/temperature", name="temperature")
     * @ParamConverter("mosque", options={"mapping": {"slug": "slug"}})
     */
    public function getTemperatureAjaxAction(Request $request, Mosque $mosque)
    {
        $temperatur = $this->get("app.weather_service")->getTemperature($mosque);
        return new Response($temperatur);
    }

}
