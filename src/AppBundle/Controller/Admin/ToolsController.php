<?php

namespace AppBundle\Controller\Admin;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Route("/admin")
 */
class ToolsController extends Controller {
        
    /**
     * @Route("/load-calendar", name="ajax_load_calendar")
     */
    public function loadCalendarAction(Request $request) {
        $calendarName = $request->query->get("calendarName");
        $calendarDir = $this->getParameter("kernel.root_dir")."/Resources/calendar/$calendarName";
        $csvFiles = glob($calendarDir . "/*.csv");
        $calendar = [];
        
        foreach ($csvFiles as $file){
            $calendar[] = array_map('str_getcsv', file($file));
        }
        
        return new JsonResponse($calendar);
    }

}
