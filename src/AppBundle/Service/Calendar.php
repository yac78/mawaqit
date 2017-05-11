<?php

namespace AppBundle\Service;

abstract class Calendar {

    const MONTHS = [
        'january' => 31,
        'february' => 29,
        'march' => 31,
        'april' => 30,
        'mai' => 31,
        'june' => 30,
        'july' => 31,
        'august' => 31,
        'september' => 30,
        'october' => 31,
        'november' => 30,
        'december' => 31,
    ];

    /**
     * Format posted calendar to target array to be inserted in DB
     * @param array $postedCalendarData
     * @return array
     */
    static function format(array $postedCalendarData) {
        $caledar = [];
        $empty = true;
        foreach ($postedCalendarData as $key => $value) {
            if (!empty($value)) {
                $empty = false;
            }
            $keys = explode('_', $key);
            $caledar[$keys[0]][$keys[1]][$keys[2]] = $value;
        }

        if ($empty) {
            return null;
        }

        return $caledar;
    }

    /**
     * Format calendar data from DB to html input data
     * @param array $calendarData
     * @return array
     */
    static function reverse(array $calendarData) {
        $inputData = [];
        foreach ($calendarData as $month => $days) {
            foreach ($days as $day => $prayers) {
                foreach ($prayers as $prayerIndex => $prayer) {
                    $key = implode('_', [$month, $day, $prayerIndex]);
                    $inputData[$key] = $prayer;
                }
            }
        }
        return $inputData;
    }

}
