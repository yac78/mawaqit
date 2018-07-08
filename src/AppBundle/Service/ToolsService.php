<?php

namespace AppBundle\Service;

use AppBundle\Entity\Mosque;
use AppBundle\Exception\GooglePositionException;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Intl\Intl;

class ToolsService
{


    /**
     * @var EntityManager
     */
    private $em;


    /**
     * @var GoogleService
     */
    private $googleService;

    public function __construct(ContainerInterface $container)
    {
        ini_set('memory_limit', '512M');
        ini_set('max_execution_time', 120);
        $this->em = $container->get("doctrine.orm.entity_manager");
        $this->googleService = $container->get("app.google_service");
    }


    public function updateLocations($offset = 0)
    {
        $mosques = $this->em
            ->getRepository("AppBundle:Mosque")
            ->createQueryBuilder("m")
            ->where("m.city IS NOT NULL")
            ->andWhere("m.zipcode IS NOT NULL")
            ->andWhere("m.address IS NOT NULL")
            ->andWhere("m.country IS NOT NULL")
            ->andWhere("m.type = 'mosque'")
            ->setFirstResult($offset)
            ->setMaxResults(5)
            ->getQuery()
            ->getResult();

        /**
         * @var $mosque Mosque
         */

        $editedMosques = [];
        foreach ($mosques as $mosque) {

            $latBefore = $mosque->getConfiguration()->getLatitude();
            $lonBefore = $mosque->getConfiguration()->getLongitude();

            $status = "OK";
            try {
                $gps = $this->googleService->getPosition($mosque);
                $mosque->getConfiguration()->setLatitude($gps->lat);
                $mosque->getConfiguration()->setLongitude($gps->lng);
                $this->em->persist($mosque);

            } catch (GooglePositionException $e) {
                $status = "KO";
            }

            $editedMosques[] = $mosque->getId() . ',' . $mosque->getName() . ',' . $mosque->getCity() . ',' . $mosque->getCountry() . ',' . $latBefore . ',' . $lonBefore . ',' . $mosque->getConfiguration()->getLatitude() . ',' . $mosque->getConfiguration()->getLongitude() . ',' . $status;
        }

        file_put_contents("/tmp/rapport_gps_$offset.csv", implode("\t\n", $editedMosques));
        $this->em->flush();
    }


    public function updateFrCalendar()
    {
        ini_set('memory_limit', '512M');
        $mosques = $this->em
            ->getRepository("AppBundle:Mosque")
            ->createQueryBuilder("m")
            ->innerJoin("m.configuration", "m")
            ->where("c.sourceCalcul = 'calendar'")
            ->andWhere("c.dst = 2")
            ->getQuery()
            ->getResult();

        /**
         * @var $mosqque Mosque
         */

        $editedMosques = [];
        foreach ($mosques as $mosqque) {
            $cal = $mosqque->getConfiguration()->getCalendar();
            if (!empty($cal) && is_array($cal)) {
                $editedMosques[] = $mosqque->getName() . ',' . $mosqque->getCity() . ',' . $mosqque->getCountry() . ',' . $mosqque->getUser()->getEmail();
                for ($month = 3; $month <= 9; $month++) {
                    for ($day = 1; $day <= count($cal[$month]); $day++) {
                        for ($prayer = 1; $prayer <= count($cal[$month][$day]); $prayer++) {
                            if (!empty($cal[$month][$day][$prayer])) {
                                $cal[$month][$day][$prayer] = $this->removeOneHour($cal[$month][$day][$prayer]);
                            }
                        }
                    }
                }


                $mosqque->getConfiguration()->setCalendar($cal);
                $this->em->persist($mosqque);
            }
        }

        file_put_contents("/tmp/rapport.csv", implode("\t\n", $editedMosques));
        $this->em->flush();

    }

    private function removeOneHour($time)
    {
        try {
            $date = new \DateTime("2018-03-01 $time:00");
            $date->sub(new \DateInterval('PT1H'));
            return $date->format("H:i");
        } catch (\Exception $e) {

        }
        return $time;
    }

    public static function getCountryNameByCode($countryCode)
    {
        $countries = Intl::getRegionBundle()->getCountryNames("en");

        return array_key_exists($countryCode, $countries)
            ? strtoupper($countries[$countryCode])
            : $countryCode;
    }

}
