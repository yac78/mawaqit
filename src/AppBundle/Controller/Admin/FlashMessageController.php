<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Mosque;
use AppBundle\Form\FlashMessageType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * @Route("/admin")
 */
class FlashMessageController extends Controller
{
    /**
     * @Route("/mosque/{mosque}/flash-message/edit", name="flash_message_edit")
     */
    public function editAction(Request $request, Mosque $mosque)
    {
        $user = $this->getUser();
        if (!$user->isAdmin()) {
            if ($user !== $mosque->getUser()) {
                throw new AccessDeniedException;
            }
        }

        $message = $mosque->getFlashMessage();
        $form = $this->createForm(FlashMessageType::class, $message);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $mosque->setFlashMessage($message);
            $mosque->setUpdated(new \DateTime());
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            $this->addFlash('success', "form.edit.success");
            return $this->redirectToRoute('message_index', ['mosque' => $mosque->getId(), '_fragment' => 'flashMessage']);
        }

        return $this->render('flash_message/edit.html.twig', [
            'mosque' => $mosque,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/mosque/{mosque}/flash-message/delete", name="flash_message_delete")
     */
    public function deleteAction(Mosque $mosque)
    {
        $user = $this->getUser();
        if (!$user->isAdmin()) {
            if ($user !== $mosque->getUser()) {
                throw new AccessDeniedException;
            }
        }
        $message = $mosque->getFlashMessage();
        $message->setContent(null);
        $mosque->setUpdated(new \DateTime());
        $em = $this->getDoctrine()->getManager();
        $em->flush();
        $this->addFlash('success', "form.delete.success");

        return $this->redirectToRoute('message_index', ['mosque' => $mosque->getId(), '_fragment' => 'flashMessage']);
    }
}
