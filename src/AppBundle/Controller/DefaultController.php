<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Mosque;
use AppBundle\Entity\Parameters;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Swift_Message;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Yaml\Yaml;

class DefaultController extends Controller
{
    /**
     * @Route("", name="homepage")
     * @Cache(public=true, maxage="86400", smaxage="86400", expires="+86400 sec")
     * @param Request $request
     * @param EntityManagerInterface $em
     *
     * @return Response
     * @throws NonUniqueResultException
     */
    public function indexAction(Request $request, EntityManagerInterface $em)
    {
        if ($this->get('app.request_service')->isLocal()) {
            throw new NotFoundHttpException();
        }

        $mosqueRepo = $em->getRepository("AppBundle:Mosque");
        $paginator = $this->get('knp_paginator');
        $page = $request->query->getInt('page', 1);
        $page = $page > 0 ? $page : 1;
        $mosquesWithImage = $paginator->paginate($mosqueRepo->getMosquesWithImageQb(), 1, $page * 9);
        $totalMosquesCount = $mosqueRepo->getCount();
        $mosqueNumberByCountry = $mosqueRepo->getNumberByCountry();
        $countries = Yaml::parseFile($this->getParameter("kernel.root_dir") . "/Resources/yaml/countries.yml");
        foreach ($mosqueNumberByCountry as $key => $value) {
            $mosqueNumberByCountry[$key]["coordinates"] = null;
            if (isset($countries[$value['country']])) {
                $mosqueNumberByCountry[$key]["coordinates"] = $countries[$value['country']];
            }
        }

        return $this->render('default/index.html.twig', [
            "totalMosquesCount" => $totalMosquesCount,
            "mosquesWithImage" => $mosquesWithImage,
            "mosqueNumberByCountry" => $mosqueNumberByCountry,
            "nextPage" => ++$page,
            "faqs" => $em->getRepository('AppBundle:Faq')->getPublicFaq()
        ]);
    }


    /**
     * @Route("/manual", name="manual")
     */
    public function manualAction(EntityManagerInterface $em)
    {
        if ($this->get('app.request_service')->isLocal()) {
            throw new NotFoundHttpException();
        }

        $parametersRepo = $em->getRepository(Parameters::class);
        $systemImageLink = $parametersRepo->findOneBy(["key" => "system_image_link"]);
        $raspberry3Link = $parametersRepo->findOneBy(["key" => "raspberry_3_link"]);
        $raspberry4Link = $parametersRepo->findOneBy(["key" => "raspberry_4_link"]);
        $rtcLink = $parametersRepo->findOneBy(["key" => "rtc_link"]);

        return $this->render('default/manual.html.twig', [
            "system_image_link" => $systemImageLink instanceof Parameters ? $systemImageLink->getValue() : "#",
            "raspberry_3_link" => $raspberry3Link instanceof Parameters ? $raspberry3Link->getValue() : "#",
            "raspberry_4_link" => $raspberry4Link instanceof Parameters ? $raspberry4Link->getValue() : "#",
            "rtc_link" => $rtcLink instanceof Parameters ? $rtcLink->getValue() : "#",
        ]);
    }

    /**
     * @Route("legal-notice", name="legal_notice")
     * @Cache(public=true, maxage="86400", smaxage="86400", expires="+86400 sec")
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

        $to = $this->getParameter('support_email');
        $emailSubject = "Contact depuis le site web";
        $emailBody = "Email envoyé depuis le site internet.<br><br>"
            . "Voici le détail:<br><br>Nom: $name<br><br>"
            . "Email: $emailAddress<br><br>"
            . "Tél: $phone<br><br>"
            . "Message:<br>$message";

        $message = Swift_Message::newInstance()
            ->setSubject($emailSubject)
            ->setFrom($emailAddress)
            ->setTo($to)
            ->setBody($emailBody, 'text/html');

        $this->get('mailer')->send($message);
        return new Response();
    }

    /**
     * get users by search term
     *
     * @param Request $request
     * @Route("/search-ajax", name="public_mosque_search_ajax")
     *
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
     *
     * @param $country
     * @Route("/cities/{country}", name="cities_country_ajax", options={"i18n"="false"})
     *
     * @return JsonResponse
     */
    public function citiesByCountryAjaxAction($country)
    {
        $em = $this->getDoctrine()->getManager();
        $cities = $em->getRepository("AppBundle:Mosque")->getCitiesByCountry($country);
        return new JsonResponse($cities);
    }

    /**
     * @Route("/map/mosques/{countryCode}", name="mosques_map_ajax", options={"i18n"="false"})
     * @param string                 $countryCode
     * @param EntityManagerInterface $em
     *
     * @return JsonResponse
     */
    public function getMosquesForMapAction($countryCode, EntityManagerInterface $em)
    {
        $mosquesForMap = $em->getRepository(Mosque::class)->getMosquesForMap($countryCode);
        return new JsonResponse($mosquesForMap);
    }

}
