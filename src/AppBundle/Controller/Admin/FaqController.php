<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Faq;
use AppBundle\Form\FaqType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

/**
 * @Route("/admin/faq")
 */
class FaqController extends Controller
{

    /**
     * @Route("", name="faq")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $faqRepo = $em->getRepository('AppBundle:Faq');
        $qb = $faqRepo->findAll();

        $paginator = $this->get('knp_paginator');
        $faqs = $paginator->paginate($qb, $request->query->getInt('page', 1), 10);
        return $this->render('faq/index.html.twig', [
            "faqs" => $faqs
        ]);
    }

    /**
     * @Route("/create", name="faq_create")
     */
    public function createAction(Request $request)
    {
        $faq = new Faq();

        $form = $this->createForm(FaqType::class, $faq);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($faq);
            $em->flush();
            $this->addFlash('success', "form.create.success");

            return $this->redirectToRoute('faq');
        }

        return $this->render('faq/create.html.twig', [
            'form' => $form->createView(),
        ]);

    }

    /**
     * @Route("/{id}/edit", name="faq_edit")
     */
    public function editAction(Request $request, Faq $faq)
    {

        $form = $this->createForm(FaqType::class, $faq);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $faq = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($faq);
            $em->flush();
            $this->addFlash('success', "form.edit.success");

            return $this->redirectToRoute('faq');
        }

        return $this->render('faq/edit.html.twig', [
            "faq" => $faq,
            'form' => $form->createView(),
        ]);

    }

    /**
     * @Route("/{id}/delete", name="faq_delete")
     */
    public function deleteAction(Faq $faq)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($faq);
        $em->flush();
        $this->addFlash('success', "form.delete.success");
        return $this->redirectToRoute('faq');

    }

    /**
     * @param Request $request
     * @Route("/faq/sort", name="faq_sort")
     * @Method("PUT")
     * @return JsonResponse
     */
    public function sortAction(Request $request)
    {
//        $em = $this->getDoctrine()->getManager();
//        $message = $em->getRepository('AppBundle:Message')->find((int)$request->request->get("id"));
//        $message->setPosition((int)$request->request->get("position"));
//        $em->persist($message);
//        $em->flush();

        return new JsonResponse();
    }

}
