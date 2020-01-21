<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Mosque;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * @Route("/messages")
 */
class MessageController extends Controller
{

    /**
     * @Route("/{slug}", name="messages")
     * @ParamConverter("mosque", options={"mapping": {"slug": "slug"}})
     */
    public function indexAction(Mosque $mosque)
    {
        return $this->render("message/show_message_slider.html.twig", ['mosque' => $mosque]);
    }

}
