<?php

namespace AppBundle\Service;

use AppBundle\Service\Api\WeatherApi;
use AppBundle\Entity\Mosque;

class WeatherService extends WeatherApi {

    private static $hours = ["02", "05", "08", "11", "14", "17", "20", "23"];

    /**
     * get temperature of the mosque city, in celsius
     * @param Mosque $mosque
     * @return type
     */
    function getTemperature(Mosque $mosque) {
        $position = $mosque->getGpsCoordinates();
        $position = "_ll=" . $position["lat"] . "," . $position["lon"];
        $res = $this->call("GET", $position);

        if ($res instanceof \stdClass && $res->request_state === 200) {
            $date = new \DateTime();
            $currentHour = $date->format("H");
            foreach (self::$hours as $hour) {
                if ($currentHour <= $hour) {
                    $index = $date->format("Y-m-d $hour:00:00");
                    if (isset($res->$index->temperature->sol)) {
                        return number_format(round($res->$index->temperature->sol - 273.15), 0);
                    }
                }
            }
        }

        return "";
    }

}
