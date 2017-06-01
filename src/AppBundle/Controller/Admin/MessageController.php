<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Message;
use AppBundle\Entity\Mosque;
//use AppBundle\Form\MessageType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * @Route("/admin/mosque/{_locale}", requirements={"_locale"= "en|fr|ar"}, defaults={"_local"="fr"})
 */
class MessageController extends Controller {

    /**
     * @Route("/{mosque}/messages/", name="message_index")
     */
    public function indexAction(Request $request, Mosque $mosque) {

        $user = $this->getUser();
        if (!$user->isAdmin() && $user !== $mosque->getUser()) {
            throw new AccessDeniedException;
        }

        $em = $this->getDoctrine()->getManager();
        $messages = $em->getRepository("AppBundle:Message")->findBy(["mosque" => $mosque]);

        return $this->render('message/index.html.twig', [
                    "mosque" => $mosque,
                    "messages" => $messages
        ]);
    }

    /**
     * @Route("/delete/{id}", name="message_delete")
     */
    public function deleteAction(Request $request, Message $message) {
        $user = $this->getUser();
        if (!$user->isAdmin() && $user !== $message->getMosque()->getUser()) {
            throw new AccessDeniedException;
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($message);
        $em->flush();
        $this->addFlash('success', $this->get("translator")->trans("form.delete.success", [
                    "%object%" => "du message", "%name%" => $message->getTitle()
        ]));
        return $this->redirectToRoute('message_index', ['mosque'=>$message->getMosque()->getId()]);
    }

}
