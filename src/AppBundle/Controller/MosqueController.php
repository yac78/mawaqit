<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Mosque;
use AppBundle\Entity\Configuration;
use AppBundle\Form\ConfigurationType;
use AppBundle\Form\MosqueType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedException;
use AppBundle\Service\Calendar;

/**
 * @Route("/admin/mosque")
 */
class MosqueController extends Controller {

    /**
     * @Route("/", name="mosque_index")
     */
    public function indexAction() {

        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $mosques = $em->getRepository("AppBundle:Mosque")->getMosquesByUser($user);
        return $this->render('mosque/index.html.twig', [
                    "mosques" => $mosques,
                    "baseUrl" => $this->getParameter('base_url')
        ]);
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

        $configuration = $mosque->getConfiguration();
        if (!$configuration instanceof Configuration) {
            $em = $this->getDoctrine()->getManager();
            $configuration = new Configuration();
            $mosque->setConfiguration($configuration);
            $em->persist($mosque);
            $em->flush();
        }

        $form = $this->createForm(ConfigurationType::class, $configuration);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $configuration = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($configuration);
            $em->flush();
            $this->addFlash('success', $this->get("translator")->trans("form.edit.success", [
                        "%object%" => "de la mosquée", "%name%" => $mosque->getName()
            ]));

            return $this->redirectToRoute('mosque_index');
        }


        return $this->render('mosque/configure.html.twig', [
                    'months' => Calendar::MONTHS,
                    'mosque' => $mosque,
                    'form' => $form->createView()
        ]);
    }

}
