<?php

namespace AppBundle\Service;

use AppBundle\Service\Api\GoogleApi;
use Monolog\Logger;
use AppBundle\Exception\GooglePositionException;

class GoogleService extends GoogleApi {

    const PATH_GEOCODE = "/geocode/json";
    const PATH_TEMEZONE = "/timezone/json";

    /**
     * @var Logger
     */
    private $logger;

    /**
     * Get longitude and latitude
     * @return array
     */
    function getPosition($address) {
        $url = self::PATH_GEOCODE . "?address=$address";
        $res = $this->get($url);

        if ($res instanceof \stdClass && isset($res->results[0]->geometry->location)) {
            return $res->results[0]->geometry->location;
        }
        
        $this->logger->error("Impossible de localiser l'adresse $address");
        throw new GooglePositionException();
    }
    
    /**
     * @return integer
     */
    function getTimezoneOffset($longitude, $latitude) {
        $url = self::PATH_TEMEZONE . "?location=$latitude,$longitude&timestamp=".time();
        $res = $this->get($url);

        if ($res instanceof \stdClass && isset($res->rawOffset)) {
            return $res->rawOffset/3600;
        }
        
        $this->logger->error("Impossible de récupérer le timezone de longitude = $longitude et latitude = $latitude");
    }

    function setLogger(Logger $logger) {
        $this->logger = $logger;
    }
}
