<?php

namespace AppBundle\Controller\Backoffice;

use AppBundle\Entity\FlashMessage;
use AppBundle\Entity\Mosque;
use AppBundle\Form\FlashMessageType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * @Route("/backoffice")
 */
class FlashMessageController extends Controller
{
    /**
     * @Route("/mosque/{mosque}/flash-message/edit", name="flash_message_edit")
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param Mosque $mosque
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request, EntityManagerInterface $em, Mosque $mosque)
    {
        $user = $this->getUser();
        if (!$this->isGranted("ROLE_ADMIN")) {
            if ($user !== $mosque->getUser()) {
                throw new AccessDeniedException;
            }
        }

        $message = $mosque->getFlashMessage();
        $form = $this->createForm(FlashMessageType::class, $message);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $mosque->setFlashMessage($form->getData());
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
     * @param EntityManagerInterface $em
     * @param Mosque $mosque
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(EntityManagerInterface $em, Mosque $mosque)
    {
        $user = $this->getUser();
        if (!$this->isGranted("ROLE_ADMIN")) {
            if ($user !== $mosque->getUser()) {
                throw new AccessDeniedException;
            }
        }

        $mesage = $mosque->getFlashMessage();
        if ($mesage instanceof FlashMessage) {
            $em->remove($mesage);
            $mosque->setUpdated(new \DateTime());
            $em->flush();
            $this->addFlash('success', "form.delete.success");
        }

        return $this->redirectToRoute('message_index', ['mosque' => $mosque->getId(), '_fragment' => 'flashMessage']);
    }
}
