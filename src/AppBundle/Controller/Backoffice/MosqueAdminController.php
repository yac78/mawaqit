<?php

namespace AppBundle\Controller\Backoffice;

use AppBundle\Entity\Mosque;
use AppBundle\Form\MosqueSuspensionType;
use AppBundle\Service\MailService;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * @Route("/backoffice/admin/mosque")
 */
class MosqueAdminController extends Controller
{

    /**
     * @Route("/clone/{id}", name="mosque_clone")
     */
    public function cloneAction(Mosque $mosque, EntityManagerInterface $em)
    {
        $user = $this->getUser();
        $clonedMosque = clone $mosque;
        $clonedMosque->setUser($user);
        $em->persist($clonedMosque);
        $em->flush();
        $this->addFlash('success', "form.clone.success");
        return $this->redirectToRoute('mosque_edit', ['id' => $clonedMosque->getId()]);
    }

    /**
     * @Route("/validate/{id}", name="mosque_validate")
     * @param Mosque $mosque
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function validateMosqueAction(Mosque $mosque)
    {
        $this->get('app.mosque_service')->validate($mosque);
        $this->addFlash('success', 'la mosquée ' . $mosque->getName() . ' a bien été validée');
        return $this->redirectToRoute("mosque_index");
    }

    /**
     * @Route("/suspend/{id}", name="mosque_suspend")
     * @Method({"GET", "POST"})
     * @param Mosque  $mosque
     * @param Request $request
     *
     * @return Response
     */
    public function suspendMosqueAction(Mosque $mosque, Request $request, MailService $mailService)
    {

        $user = $this->getUser();
        if (!$user->isAdmin() && ($user !== $mosque->getUser() || !$mosque->isValidated())) {
            throw new AccessDeniedException();
        }

        $form = $this->createForm(MosqueSuspensionType::class, $mosque);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            $mailService->mosqueSuspended($mosque);
            $this->addFlash('success', 'la mosquée ' . $mosque->getName() . ' a bien été suspendue');
            return $this->redirectToRoute('mosque_index');
        }

        return $this->render('mosque/suspend.html.twig', [
            'mosque' => $mosque,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/check/{id}", name="mosque_check")
     * @param Mosque $mosque
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws @see  MailService->checkMosque
     */
    public function checkMosqueAction(Mosque $mosque)
    {
        $this->get('app.mosque_service')->check($mosque);
        $this->addFlash('success',
            'Le mail de vérification pour la mosquée ' . $mosque->getName() . ' a bien été envoyé');
        return $this->redirectToRoute("mosque_index");
    }

    /**
     * @Route("/duplicated/{id}", name="mosque_duplicated")
     * @param Mosque $mosque
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws @see  MailService->duplicatedMosque
     */
    public function duplicatedMosqueAction(Mosque $mosque)
    {
        $this->get('app.mosque_service')->duplicated($mosque);
        $this->addFlash('success',
            'Le mail de vérification pour la mosquée ' . $mosque->getName() . ' a bien été envoyé');
        return $this->redirectToRoute("mosque_index");
    }

    /**
     * @Route("/reject-photo/{id}", name="mosque_reject_screen_photo")
     * @param Mosque $mosque
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws @see  MailService->rejectScreenPhoto
     */
    public function rejectScreenPhotoAction(Mosque $mosque)
    {
        $this->get('app.mosque_service')->rejectScreenPhoto($mosque);
        $this->addFlash('success',
            'La photo de l\'écran pour la mosquée ' . $mosque->getName() . ' a bien été supprimée');
        return $this->redirectToRoute("mosque_index");
    }

    /**
     * @Route("/accept-photo/{id}", name="mosque_accept_screen_photo")
     * @param Mosque $mosque
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function acceptScreenPhotoAction(Mosque $mosque, EntityManagerInterface $em)
    {
        $mosque->setStatus(Mosque::STATUS_VALIDATED);
        $em->flush();
        return $this->redirectToRoute("mosque_index");
    }
}
