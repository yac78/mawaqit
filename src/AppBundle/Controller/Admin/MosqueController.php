<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Mosque;
use AppBundle\Entity\Configuration;
use AppBundle\Form\ConfigurationType;
use AppBundle\Form\MosqueType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use AppBundle\Service\Calendar;
use AppBundle\Exception\GooglePositionException;
use AppBundle\Service\MailService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * @Route("/admin/mosque/{_locale}", requirements={"_locale"= "en|fr|ar"}, defaults={"_local"="fr"})
 */
class MosqueController extends Controller {

    /**
     * @Route("/", name="mosque_index")
     */
    public function indexAction(Request $request) {

        $search = $request->query->get("search");
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $mosques = $em->getRepository("AppBundle:Mosque")->getMosquesByUser($user, $search);
        return $this->render('mosque/index.html.twig', [
                    "mosques" => $mosques,
                    "languages" => $this->getParameter('languages')
        ]);
    }

    /**
     * @Security("has_role('ROLE_ADMIN')")
     * @Route("/force-update-all", name="mosque_force_update_all")
     */
    public function forceUpdateAllAction() {
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $em->getRepository("AppBundle:Mosque")->forceYpdateAll();
        $this->addFlash('success', $this->get("translator")->trans("mosque.force_update_all.success"));
        return $this->redirectToRoute('mosque_index');
    }

    /**
     * @Route("/create", name="mosque_create")
     */
    public function createAction(Request $request) {

        $mosque = new Mosque();
        $form = $this->createForm(MosqueType::class, $mosque);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $mosque = $form->getData();
            $mosque->setUser($this->getUser());
            $em = $this->getDoctrine()->getManager();
            $em->persist($mosque);
            $em->flush();
            $mailBody = $this->renderView(MailService::TEMPLATE_MOSQUE_CREATED, ['mosque' => $mosque]);
            $this->get("app.mail_service")->mosqueCreated($mosque, $mailBody);
            $this->addFlash('success', $this->get("translator")->trans("form.create.success", [
                        "%object%" => "de la mosquée", "%name%" => $mosque->getName()
            ]));

            return $this->redirectToRoute('mosque_index');
        }

        return $this->render('mosque/create.html.twig', [
                    'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/edit/{id}", name="mosque_edit")
     */
    public function editAction(Request $request, Mosque $mosque) {

        $user = $this->getUser();
        if (!$user->isAdmin() && $user !== $mosque->getUser()) {
            throw new AccessDeniedException();
        }

        $form = $this->createForm(MosqueType::class, $mosque);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $mosque = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($mosque);
            $em->flush();
            $this->addFlash('success', $this->get("translator")->trans("form.edit.success", [
                        "%object%" => "de la mosquée", "%name%" => $mosque->getName()
            ]));

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
    public function deleteAction(Request $request, Mosque $mosque) {
        $user = $this->getUser();
        if (!$user->isAdmin() && $user !== $mosque->getUser()) {
            throw new AccessDeniedException;
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($mosque);
        $em->flush();
        $this->addFlash('success', $this->get("translator")->trans("form.delete.success", [
                    "%object%" => "de la mosquée", "%name%" => $mosque->getName()
        ]));
        return $this->redirectToRoute('mosque_index');
    }

    /**
     * @Route("/configure/{id}", name="mosque_configure")
     */
    public function configureAction(Request $request, Mosque $mosque) {

        $user = $this->getUser();
        if (!$user->isAdmin() && $user !== $mosque->getUser()) {
            throw new AccessDeniedException;
        }
        $em = $this->getDoctrine()->getManager();

        $configuration = $mosque->getConfiguration();

        if (!$configuration instanceof Configuration) {
            $configuration = new Configuration();
            $configuration->setMosque($mosque);
            $em->persist($configuration);
            $em->flush();
            $em->refresh($mosque);
        }

        $form = $this->createForm(ConfigurationType::class, $configuration);

        try {
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $configuration = $form->getData();
                $em->persist($configuration);
                $em->flush();
                $this->addFlash('success', $this->get("translator")->trans("form.configure.success", [
                            "%object%" => "de la mosquée", "%name%" => $mosque->getName()
                ]));
                return $this->redirectToRoute('mosque_index');
            }
        } catch (GooglePositionException $exc) {
            $this->addFlash('danger', $this->get("translator")->trans("form.configure.geocode_error", [
                        "%address%" => $mosque->getCityZipCode()
            ]));
        }
        return $this->render('mosque/configure.html.twig', [
                    'months' => Calendar::MONTHS,
                    'mosque' => $mosque,
                    'form' => $form->createView()
        ]);
    }

}
