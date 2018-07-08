<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Configuration;
use AppBundle\Entity\Mosque;
use AppBundle\Exception\GooglePositionException;
use AppBundle\Form\ConfigurationType;
use AppBundle\Form\MosqueSearchType;
use AppBundle\Form\MosqueType;
use AppBundle\Service\Calendar;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/admin/mosque")
 */
class MosqueController extends Controller
{

    /**
     * @Route(name="mosque_index")
     */
    public function indexAction(Request $request)
    {

        $search = $request->query->all();
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $mosqueRepository = $em->getRepository("AppBundle:Mosque");
        $qb = $mosqueRepository->search($user, $search);

        $paginator = $this->get('knp_paginator');
        $mosques = $paginator->paginate($qb, $request->query->getInt('page', 1), 10);
        $form = $this->createForm(MosqueSearchType::class);

        $result = [
            "form" => $form->createView(),
            "mosques" => $mosques,
            "languages" => $this->getParameter('visible_languages')
        ];

        return $this->render('mosque/index.html.twig', $result);
    }

    /**
     * @Route("/create", name="mosque_create")
     */
    public function createAction(Request $request)
    {
        $mosque = new Mosque();
        $form = $this->createForm(MosqueType::class, $mosque);

        try {
            $form->handleRequest($request);
        } catch (GooglePositionException $exc) {
            $form->addError(new FormError($this->get("translator")->trans("form.configure.geocode_error", [
                "%address%" => $mosque->getLocalisation()
            ])));
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $mosque->setUser($this->getUser());
            $em->persist($mosque);
            $em->flush();

            // send mail if mosque
            if ($mosque->isMosque()) {
                $totalMosqueCount = $em->getRepository("AppBundle:Mosque")->getCount();
                $this->get("app.mail_service")->mosqueCreated($mosque, $totalMosqueCount);
            }

            $this->addFlash('success', "form.create.success");
            return $this->redirectToRoute('mosque_index');
        }


        return $this->render('mosque/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/edit/{id}", name="mosque_edit")
     */
    public function editAction(Request $request, Mosque $mosque)
    {

        $user = $this->getUser();
        if (!$user->isAdmin() && $user !== $mosque->getUser()) {
            throw new AccessDeniedException();
        }

        $form = $this->createForm(MosqueType::class, $mosque);

        try {
            $form->handleRequest($request);
        } catch (GooglePositionException $exc) {
            $form->addError(new FormError($this->get("translator")->trans("form.configure.geocode_error", [
                "%address%" => $mosque->getLocalisation()
            ])));
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $mosque = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($mosque);
            $em->flush();
            $this->addFlash('success', "form.edit.success");

            return $this->redirectToRoute('mosque_index');
        }
        return $this->render('mosque/edit.html.twig', [
            'mosque' => $mosque,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/delete/{id}", name="mosque_delete")
     */
    public function deleteAction(Mosque $mosque)
    {
        $user = $this->getUser();
        if (!$user->isAdmin() && $user !== $mosque->getUser()) {
            throw new AccessDeniedException;
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($mosque);
        $em->flush();
        $this->addFlash('success', "form.delete.success");
        return $this->redirectToRoute('mosque_index');
    }

    /**
     * @Route("/clone/{id}", name="mosque_clone")
     */
    public function cloneAction(Mosque $mosque)
    {
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $clonedMosque = clone $mosque;
        $clonedMosque->setId(null);
        $clonedMosque->setUser($user);
        $clonedMosque->setSlug(null);
        $clonedMosque->setImage1(null);
        $clonedMosque->setImage2(null);
        $clonedMosque->setImage3(null);
        $clonedMosque->setCreated(null);
        $clonedMosque->setUpdated(null);
        $clonedMosque->clearMessages();
        $clonedConfiguration = clone $clonedMosque->getConfiguration();
        $clonedConfiguration->setId(null);
        $clonedMosque->setConfiguration($clonedConfiguration);
        $em->persist($clonedMosque);
        $em->flush();
        $this->addFlash('success', "form.clone.success");
        return $this->redirectToRoute('mosque_edit', ['id' => $clonedMosque->getId()]);
    }

    /**
     * Force refresh page by updating updated_at
     * @Route("/refresh/{id}", name="mosque_refresh")
     */
    public function refreshAction(Mosque $mosque)
    {
        $em = $this->getDoctrine()->getManager();
        $mosque->setUpdated(new \Datetime());
        $em->flush();
        return new Response();
    }

    /**
     * @Route("/{id}/configure", name="mosque_configure")
     */
    public function configureAction(Request $request, Mosque $mosque)
    {

        $user = $this->getUser();
        if (!$user->isAdmin() && $user !== $mosque->getUser()) {
            throw new AccessDeniedException;
        }
        $em = $this->getDoctrine()->getManager();

        $configuration = $mosque->getConfiguration();

        $form = $this->createForm(ConfigurationType::class, $configuration);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($configuration);
            $em->flush();
            return $this->redirectToRoute('mosque', [
                'slug' => $mosque->getSlug()
            ]);
        }

        return $this->render('mosque/configure.html.twig', [
            'months' => Calendar::MONTHS,
            'predefinedCalendars' => $this->get("app.mosque_service")->getCalendarList(),
            'mosque' => $mosque,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/getCsvFiles/{id}", name="mosque_csv_files")
     */
    public function getCsvFilesAction(Mosque $mosque)
    {

        $zipFilePath = $this->get("app.prayer_times_service")->getFilesFromCalendar($mosque);
        if (is_file($zipFilePath)) {
            $zipFileName = $mosque->getSlug() . ".zip";
            return new BinaryFileResponse($zipFilePath, 200, ['Content-Disposition' => 'attachment; filename="' . $zipFileName . '"']);
        }

        return new Response("Cette mosquée n'as pas de calendier renseignée");
    }

    /**
     * @param Mosque $mosque
     * @param Configuration $configuration
     * @Route("/copy-calendar/mosque/{mosque}/from-configure/{configuration}", name="copy_calendar")
     * @return Response
     */
    public function copyCalendarAction(Mosque $mosque, Configuration $configuration)
    {
        $mosque->getConfiguration()
            ->setCalendar($configuration->getCalendar())
            ->setSourceCalcul(Configuration::SOURCE_CALENDAR);
        $em = $this->getDoctrine()->getManager();
        $em->persist($mosque);
        $em->flush();

        return $this->redirectToRoute("mosque_configure", [
            'id' => $mosque->getId()
        ]);
    }

    /**
     * @Route("/mosque/validate/{id}", name="mosque_validate")
     * @param Mosque $mosque
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws @see MailService->mosqueValidated
     */
    public function validateMosqueAction(Mosque $mosque)
    {
        $mosque->setStatus(Mosque::STATUS_VALIDATED);
        $em = $this->getDoctrine()->getManager();
        $em->persist($mosque);
        $em->flush();
        $this->get("app.mail_service")->mosqueValidated($mosque);
        $this->addFlash('success', 'la mosquée ' . $mosque->getName() . ' a bien été validée');
        return $this->redirectToRoute("mosque_index");
    }


    /**
     * @Route("/mosque/suspend/{id}", name="mosque_suspend")
     * @param Mosque $mosque
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws @see MailService->mosqueSuspend
     */
    public function suspendMosqueAction(Mosque $mosque)
    {
        $mosque->setStatus(Mosque::STATUS_SUSPENDED);
        $em = $this->getDoctrine()->getManager();
        $em->persist($mosque);
        $em->flush();
        $this->get("app.mail_service")->mosqueSuspended($mosque);
        $this->addFlash('success', 'la mosquée ' . $mosque->getName() . ' a bien été suspendue');
        return $this->redirectToRoute("mosque_index");
    }


    /**
     * @Route("/mosque/check/{id}", name="mosque_check")
     * @param Mosque $mosque
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws @see  MailService->checkMosque
     */
    public function checkMosqueAction(Mosque $mosque)
    {
        $mosque->setStatus(Mosque::STATUS_CHECK);
        $em = $this->getDoctrine()->getManager();
        $em->persist($mosque);
        $em->flush();
        $this->get("app.mail_service")->checkMosque($mosque);
        $this->addFlash('success', 'Le mail de vérification pour la mosquée ' . $mosque->getName() . ' a bien été envoyé');
        return $this->redirectToRoute("mosque_index");
    }

}
