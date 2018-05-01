<?php

namespace AppBundle\Service;

use AppBundle\Entity\Mosque;
use AppBundle\Service\Api\GoogleApi;
use Monolog\Logger;
use AppBundle\Exception\GooglePositionException;

class GoogleService extends GoogleApi
{

    const PATH_GEOCODE = "/geocode/json";

    /**
     * @var Logger
     */
    private $logger;

    /**
     * @var ToolsService
     */
    private $toolsService;

    /**
     * Get longitude and latitude
     * @param Mosque $mosque
     * @return array
     * @throws GooglePositionException
     */
    function getPosition(Mosque $mosque)
    {
        $address = [
            $mosque->getAddress(),
            $mosque->getZipcode(),
            $mosque->getCity(),
            $this->toolsService->getCountryNameByCode($mosque->getCountry())
        ];

        $encodedAddr = urlencode(trim(implode(' ', $address)));
        $res = $this->get(self::PATH_GEOCODE . "?address=$encodedAddr");

        if ($res instanceof \stdClass && isset($res->results[0]->geometry->location)) {
            return $res->results[0]->geometry->location;
        }

        $this->logger->error("Impossible de localiser l'adresse $encodedAddr");
        throw new GooglePositionException();
    }

    /**
     * @param Logger $logger
     */
    function setLogger(Logger $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param ToolsService $toolsService
     */
    function setToolsService(ToolsService $toolsService)
    {
        $this->toolsService = $toolsService;
    }
}
