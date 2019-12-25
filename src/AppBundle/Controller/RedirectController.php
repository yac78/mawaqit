<?php
namespace AppBundle\Controller;

use AppBundle\Entity\Mosque;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class RedirectController extends Controller
{

    /**
     * @Route("/mosque/{slug}/{_locale}", options={"i18n"="false"}, requirements={"_locale"= "en|fr|ar|tr"})
     * @ParamConverter("mosque", options={"mapping": {"slug": "slug"}})
     * @param Mosque $mosque
     * @return Response
     */
    public function mosqueDeprected1Action(Mosque $mosque)
    {
        return $this->redirectToRoute("mosque", ["slug" => $mosque->getSlug()], Response::HTTP_MOVED_PERMANENTLY);
    }

    /**
     * @Route("/{slug}/{_locale}", options={"i18n"="false"}, requirements={"_locale"= "en|fr|ar|tr"})
     * @param Mosque $mosque
     * @ParamConverter("mosque", options={"mapping": {"slug": "slug"}})
     * @return Response
     */
    public function mosqueDeprected2Action(Mosque $mosque)
    {
        return $this->redirectToRoute("mosque", ["slug" => $mosque->getSlug()], Response::HTTP_MOVED_PERMANENTLY);
    }

    /**
     * @Route("/admin/mosque")
     * @return Response
     */
    public function adminToBackofficeAction()
    {
        return $this->redirectToRoute("mosque_index", [], Response::HTTP_MOVED_PERMANENTLY);
    }

    /**
     * @Route("/admin/login")
     * @return Response
     */
    public function loginRedirectAction()
    {
        return $this->redirectToRoute("fos_user_security_login", [], Response::HTTP_MOVED_PERMANENTLY);
    }
}
