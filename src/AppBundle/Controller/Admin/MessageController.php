<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Message;
use AppBundle\Entity\Mosque;
use AppBundle\Form\MessageType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * @Route("{_locale}/admin/mosque", requirements={"_locale"= "en|fr|ar"}, defaults={"_locale"="fr"})
 */
class MessageController extends Controller {
    const MAX_MESSAGE = 8;

    /**
     * @Route("/{mosque}/message/", name="message_index")
     */
    public function indexAction(Request $request, Mosque $mosque) {

        $user = $this->getUser();
        if (!$user->isAdmin() && $user !== $mosque->getUser()) {
            throw new AccessDeniedException;
        }
        return $this->render('message/index.html.twig', [
                    "disable_add_message" => $mosque->getNbOfEnabledMessages() >= self::MAX_MESSAGE,
                    "mosque" => $mosque,
        ]);
    }

    /**
     * @Route("/{mosque}/message/create", name="message_create")
     */
    public function createAction(Request $request, Mosque $mosque) {

        if($mosque->getNbOfEnabledMessages() >= self::MAX_MESSAGE){
            throw new AccessDeniedException();
        }
        
        $user = $this->getUser();
        if (!$user->isAdmin() && $user !== $mosque->getUser()) {
            throw new AccessDeniedException;
        }

        $message = new Message();
        $form = $this->createForm(MessageType::class, $message);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $message = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $message->setMosque($mosque);
            $em->persist($message);
            $em->flush();
            $this->addFlash('success', "form.create.success");

            return $this->redirectToRoute('message_index', ['mosque' => $mosque->getId()]);
        }

        return $this->render('message/create.html.twig', [
                    "mosque" => $mosque,
                    'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{mosque}/message/edit/{message}", name="message_edit")
     */
    public function editAction(Request $request, Mosque $mosque, Message $message) {

        $user = $this->getUser();
        if (!$user->isAdmin() && $user !== $mosque->getUser()) {
            throw new AccessDeniedException;
        }

        $form = $this->createForm(MessageType::class, $message);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $message = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($message);
            $em->flush();
            $this->addFlash('success', "form.edit.success");

            return $this->redirectToRoute('message_index', ['mosque' => $mosque->getId()]);
        }

        return $this->render('message/edit.html.twig', [
                    "message" => $message,
                    "mosque" => $mosque,
                    'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/message/delete/{id}", name="message_delete")
     */
    public function deleteAction(Request $request, Message $message) {
        $user = $this->getUser();
        if (!$user->isAdmin() && $user !== $message->getMosque()->getUser()) {
            throw new AccessDeniedException;
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($message);
        $em->flush();
        $this->addFlash('success', "form.delete.success");
        return $this->redirectToRoute('message_index', ['mosque' => $message->getMosque()->getId()]);
    }

}
