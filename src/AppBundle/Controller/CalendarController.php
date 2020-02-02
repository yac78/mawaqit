<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Mosque;
use AppBundle\Service\PrayerTime;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * @Route("/calendar")
 */
class CalendarController extends Controller
{
    /**
     * @Route("/{id}", name="calendar")
     */
    public function calendarAction(Mosque $mosque, PrayerTime $prayerTime)
    {
        return $this->render('mosque/calendar.html.twig', [
            'mosque' => $mosque,
            'calendar' => $prayerTime->getCalendar($mosque),
        ]);
    }

    /**
     * @Route("/{id}/pdf", name="calendar_pdf")
     */
    public function calendarPdfAction(Mosque $mosque)
    {
        $response = $this->get("csa_guzzle.client.pdfshift")->post("convert", [
            'form_params' => [
                "source" => $this->generateUrl("calendar", ["id" => $mosque->getId()],
                    UrlGeneratorInterface::ABSOLUTE_URL)
            ]
        ]);
        $fileName = $mosque->getTitle() . ".pdf";
        return new Response($response->getBody(), Response::HTTP_OK, [
            'Content-Disposition' => 'inline; filename="' . $fileName . '"',
            'Content-Type' => 'application/pdf'
        ]);
    }

    /**
     * @Route("/{id}/csv", name="calendar_csv")
     */
    public function calendarCsvAction(Mosque $mosque)
    {

        $zipFilePath = $this->get("app.prayer_times")->getFilesFromCalendar($mosque);
        if (is_file($zipFilePath)) {
            $fileName = $mosque->getTitle() . ".zip";
            $response = new BinaryFileResponse($zipFilePath, Response::HTTP_OK,
                ['Content-Disposition' => 'attachment; filename="' . $fileName . '"']);
            $response->deleteFileAfterSend(true);
            return $response;
        }

        return new Response("An error has occured", Response::HTTP_INTERNAL_SERVER_ERROR);
    }

}
