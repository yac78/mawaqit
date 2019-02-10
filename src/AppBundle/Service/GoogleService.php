<?php

namespace AppBundle\Service;

use AppBundle\Entity\Mosque;
use AppBundle\Service\Api\GoogleApi;
use Monolog\Logger;
use AppBundle\Exception\GooglePositionException;

class GoogleService extends GoogleApi
{

    const PATH_GEOCODE = "/geocode/json";
    const PATH_TIMEZONE = "/timezone/json";

    /**
     * @var Logger
     */
    private $logger;

    /**
     * Get longitude and latitude
     * @param Mosque $mosque
     * @return array
     * @throws GooglePositionException
     */
    function getPosition(Mosque $mosque)
    {
        $addr = urlencode($mosque->getLocalisation());
        $res = $this->get(self::PATH_GEOCODE . "?address=$addr");

        if ($res instanceof \stdClass && isset($res->results[0]->geometry->location)) {
            $lng = $res->results[0]->geometry->location->lng;
            $lat = $res->results[0]->geometry->location->lat;
            $position['lng'] = $lng;
            $position['lat'] = $lat;

            // get timezone
            $res = $this->get(self::PATH_TIMEZONE . "?location=$lat,$lng");
            if ($res instanceof \stdClass && isset($res->rawOffset)) {
                $position['timezone'] = $res->rawOffset / 3600 + $res->dstOffset;
            }

            return $position;
        }

        $this->logger->warning("Impossible de localiser l'adresse $addr");
        throw new GooglePositionException();
    }

    /**
     * @param Logger $logger
     */
    function setLogger(Logger $logger)
    {
        $this->logger = $logger;
    }
}
