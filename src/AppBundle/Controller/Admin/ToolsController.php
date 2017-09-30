<?php

namespace AppBundle\Controller\Admin;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use AppBundle\Entity\Configuration;
use AppBundle\Exception\GooglePositionException;

/**
 * @Route("/admin")
 */
class ToolsController extends Controller {

    /**
     * updates all mosque gps coordiantes
     * @Security("has_role('ROLE_ADMIN')")
     * @Route("/update-gps-coordinates", name="update_gps_coordinates")
     */
    public function updateGpsCoordiantesAction() {
        $em = $this->getDoctrine()->getManager();
        $mosques = $em->getRepository("AppBundle:Mosque")->findAll();
        foreach ($mosques as $mosque) {
            $configuration = $mosque->getConfiguration();
            if ($configuration instanceof Configuration && (empty($configuration->getLatitude()) || empty($configuration->getLongitude()))) {
                try {
                    $position = $this->get("app.google_service")->getPosition($mosque->getLocalisation());
                    $configuration->setLongitude($position->lng);
                    $configuration->setLatitude($position->lat);
                    $em->persist($configuration);
                } catch (GooglePositionException $exc) {
                    
                }
            }
        }
        $em->flush();

        return $this->redirectToRoute('mosque_index');
    }

    /**
     * @Security("has_role('ROLE_ADMIN')")
     * @Route("/force-update-all", name="mosque_force_update_all")
     */
    public function forceUpdateAllAction() {
        $em = $this->getDoctrine()->getManager();
        $em->getRepository("AppBundle:Mosque")->forceUpdateAll();
        $this->addFlash('success', $this->get("translator")->trans("mosque.force_update_all.success"));
        return $this->redirectToRoute('mosque_index');
    }

}
