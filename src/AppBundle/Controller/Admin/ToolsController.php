<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Form\HijriAdjustmentType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/admin/tools")
 */
class ToolsController extends Controller
{

    /**
     * @Route(name="admin_index")
     */
    public function indexAction()
    {
        $form = $this->createForm(HijriAdjustmentType::class);

        return $this->render('tools/index.html.twig', [
            'hijriForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/force-update-all", name="mosque_force_update_all")
     */
    public function forceUpdateAllAction()
    {
        $em = $this->getDoctrine()->getManager();
        $em->getRepository("AppBundle:Mosque")->forceUpdateAll();
        $this->addFlash('success', $this->get("translator")->trans("mosque.force_update_all.success"));
        return $this->redirectToRoute('admin_index');
    }

    /**
     * Update hijri date for all mosques
     * @Route("/update-hijri-date", name="update_hijri_date")
     */
    public function updateHegerianDateAction(Request $request)
    {
        $form = $this->createForm(HijriAdjustmentType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->getRepository("AppBundle:Configuration")->updateHijriDate($form->get('hijriAdjustment')->getData());
            $this->addFlash('success',"mosque.update_hijri_date.success");
            return $this->redirectToRoute('admin_index');
        }
    }

}