<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;

class DefaultController extends Controller
{
    /**
     * @Route("", name="homepage")
     * @Cache(expires="+1 day", public=true, smaxage="86400", maxage="86400")
     * @param Request $request
     * @return Response
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function indexAction(Request $request)
    {
        if ($this->get('app.request_service')->isLocal()) {
            throw new NotFoundHttpException();
        }

        $mosqueNb = $request->query->get("mosque_nb", 6);
        $em = $this->getDoctrine()->getManager();
        $mosqueRepo = $em->getRepository("AppBundle:Mosque");
        $mosquesWithImage = $mosqueRepo->getMosquesWithImage($mosqueNb);
        $mosquesForMap = $mosqueRepo->getAllMosquesForMap();
        $totalMosquesCount = $mosqueRepo->getCount();

        return $this->render('default/index.html.twig', [
            "totalMosquesCount" => $totalMosquesCount,
            "mosquesWithImage" => $mosquesWithImage,
            "mosquesForMap" => $mosquesForMap,
            "mosqueNumberByCountry" => $mosqueRepo->getNumberByCountry(),
            "faqs" => $em->getRepository('AppBundle:Faq')->getPublicFaq(),
            "google_maps_api_key" => $this->getParameter('google_maps_api_key'),
        ]);
    }


    /**
     * @Route("/manual", name="manual")
     * @Cache(expires="+1 day", public=true, smaxage="86400", maxage="86400")
     */
    public function manualAction()
    {
        if ($this->get('app.request_service')->isLocal()) {
            throw new NotFoundHttpException();
        }
        return $this->render('default/manual.html.twig');
    }

    /**
     * @Route("legal-notice", name="legal_notice")
     */
    public function legalNoticeAction()
    {
        return $this->render('default/legal_notice.html.twig');
    }

    /**
     * @Route("/contact", name="contact-us")
     * @Method("POST")
     */
    public function contactUsAction(Request $request)
    {

        $params = $request->request->all();

        if (empty($params['name']) ||
            empty($params['email']) ||
            empty($params['phone']) ||
            empty($params['message']) ||
            !filter_var($params['email'], FILTER_VALIDATE_EMAIL)) {
            return new Response(null, 403);
        }

        $name = strip_tags(htmlspecialchars($params['name']));
        $emailAddress = strip_tags(htmlspecialchars($params['email']));
        $phone = strip_tags(htmlspecialchars($params['phone']));
        $message = strip_tags(htmlspecialchars($params['message']));

        $to = $this->getParameter('supportEmail');
        $emailSubject = "Contact depuis le site web";
        $emailBody = "Email envoyé depuis le site internet.<br><br>"
            . "Voici le détail:<br><br>Nom: $name<br><br>"
            . "Email: $emailAddress<br><br>"
            . "Tél: $phone<br><br>"
            . "Message:<br>$message";

        $message = \Swift_Message::newInstance()
            ->setSubject($emailSubject)
            ->setFrom($emailAddress)
            ->setTo($to)
            ->setBody($emailBody, 'text/html');

        $this->get('mailer')->send($message);
        return new Response();
    }

    /**
     * get users by search term
     * @param Request $request
     * @Route("/search-ajax", name="public_mosque_search_ajax")
     * @return JsonResponse
     */
    public function searchAjaxAction(Request $request)
    {
        $mosques = [];
        $query = $request->query->get("term");
        if (!empty($query)) {
            $em = $this->getDoctrine()->getManager();
            $mosques = $em->getRepository("AppBundle:Mosque")
                ->publicSearch($query)
                ->select("m.id, CONCAT(m.name, ' - ', m.city,' ', m.zipcode, ' ', m.countryFullName) AS label, m.slug")
                ->getQuery()
                ->getArrayResult();
        }

        return new JsonResponse($mosques);
    }

    /**
     * get cities by country
     * @param $country
     * @Route("/cities/{country}", name="cities_country_ajax", options={"i18n"="false"})
     * @return JsonResponse
     */
    public function citiesByCountryAjaxAction($country)
    {
        $em = $this->getDoctrine()->getManager();
        $cities = $em->getRepository("AppBundle:Mosque")->getCitiesByCountry($country);
        return new JsonResponse($cities);
    }

}
