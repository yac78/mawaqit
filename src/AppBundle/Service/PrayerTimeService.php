<?php

namespace AppBundle\Service;
use AppBundle\Entity\Mosque;
use AppBundle\Entity\Configuration;

class PrayerTimeService {

    /**
     * Get date formated depends local
     * @return array
     */
    function getCurrentFormtatedtDate($local) {
        if($local === "ar"){
            return (new \DateTime())->format("Y/m/d");
        }
        $intl = new \IntlDateFormatter($local, \IntlDateFormatter::FULL, \IntlDateFormatter::NONE);
        $dateTime = new \DateTime();
        return $intl->format($dateTime);
    }
    
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
