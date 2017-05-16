<?php

namespace AppBundle\Service;

class PrayerTimeService {

    /**
     * Get date formated
     * @return array
     */
    function getCurrentFormtatedtDate($local) {
        $intl = new \IntlDateFormatter($local, \IntlDateFormatter::FULL, \IntlDateFormatter::NONE);
        $dateTime = new \DateTime();
        return $intl->format($dateTime);
    }

}
