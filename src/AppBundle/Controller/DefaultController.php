<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller {

    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request) {

        $mosqueNb = $request->query->get("mosque_nb", 6);

        $em = $this->getDoctrine()->getManager();
        $mosques = $em->getRepository("AppBundle:Mosque")->getConfiguredMosquesWithImage($mosqueNb);
        return $this->render('default/index.html.twig', [
                    "mosques" => $mosques,
        ]);
    }

    /**
     * @Route("/contact", name="contact-us")
     * @Method("POST")
     */
    public function contactUsAction(Request $request) {

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
     * @Route("/get-hadith-of-the-day")
     */
    public function getHadithOfTheDayAjaxAction() {
        $file = $this->getParameter("kernel.root_dir") . "/Resources/xml/ryiad-essalihine.xml";
        $xmldata = simplexml_load_file($file);
        $hadiths = $xmldata->xpath('hadith');
        return new Response($hadiths[array_rand($hadiths)], 200);
    }

}
