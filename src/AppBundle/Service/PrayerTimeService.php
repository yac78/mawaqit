<?php

namespace AppBundle\Service;
use AppBundle\Entity\Mosque;

class PrayerTimeService {

    /**
     * true if mosque or configuration has been updated
     * @param Mosque $mosque
     * @param string $lastUpdatedDate
     * @return string
     */
    function mosqueHasBeenUpdated(Mosque $mosque, $lastUpdatedDate) {
        return $mosque->getUpdated() > $lastUpdatedDate;
    }

}
