<?php

namespace AppBundle\Service;

use AppBundle\Entity\Configuration;
use AppBundle\Entity\Mosque;
use AppBundle\Service\Vendor\PrayTime;

class PrayerTime
{

    /**
     * @var PrayTime
     */
    private $praytime;

    private $cacheDir;

    public function __construct($praytime, $cacheDir)
    {
        $this->praytime = $praytime;
        $this->cacheDir = $cacheDir;
    }

    /**
     * true if mosque or configuration has been updated
     * @param Mosque $mosque
     * @param string $lastUpdatedDate
     * @return string
     */
    function mosqueHasBeenUpdated(Mosque $mosque, $lastUpdatedDate)
    {
        return $mosque->getUpdated() > $lastUpdatedDate;
    }

    /**
     * transforme json calendar in csv files and compress theme in a zip file
     * @param Mosque $mosque
     * @return string the path of the zip file
     */
    function getFilesFromCalendar(Mosque $mosque)
    {
        $conf = $mosque->getConfiguration();

        if ($conf->isApi()) {
            $path = $this->cacheDir . "/" . $mosque->getId();
            if (!is_dir($path)) {
                mkdir($path);
            }

            if ($conf->getPrayerMethod() !== Configuration::METHOD_CUSTOM) {
                $this->praytime->setCalcMethod($conf->getPrayerMethod());
            }
            if ($conf->getPrayerMethod() === Configuration::METHOD_CUSTOM) {
                $this->praytime->setFajrAngle($conf->getFajrDegree());
                $this->praytime->setIshaAngle($conf->getIshaDegree());
            }
            $this->praytime->setAsrMethod($conf->getAsrMethod());
            $this->praytime->setHighLatsMethod($conf->getHighLatsMethod());

            foreach (Calendar::MONTHS as $monthIndex => $days) {
                $str = "Day,Fajr,Shuruk,Duhr,Asr,Maghrib,Isha\n";
                for ($day = 1; $day <= $days; $day++) {
                    $date = strtotime(date('Y') . '-' . ($monthIndex + 1) . '-' . $day);
                    $prayers = $this->praytime->getPrayerTimes($date, $conf->getLatitude(), $conf->getLongitude(), $conf->getTimezone());
                    unset($prayers[5]);
                    $str .= "$day," . implode(",", $prayers) . "\n";
                }
                $fileName = str_pad($monthIndex + 1, 2, "0", STR_PAD_LEFT) . ".csv";
                file_put_contents("$path/$fileName", $str);
            }

            return $this->getZipFile($mosque, $path);
        }

        if ($conf->isCalendar()) {
            $calendar = $conf->getCalendar();
            if (is_array($calendar)) {
                $path = $this->cacheDir . "/" . $mosque->getId();
                if (!is_dir($path)) {
                    mkdir($path);
                }
                foreach ($calendar as $key => $monthIndex) {
                    $str = "Day,Fajr,Shuruk,Duhr,Asr,Maghrib,Isha\n";
                    foreach ($monthIndex as $day => $prayers) {
                        $str .= "$day," . implode(",", $prayers) . "\n";
                    }
                    $fileName = str_pad($key + 1, 2, "0", STR_PAD_LEFT) . ".csv";
                    file_put_contents("$path/$fileName", $str);
                }

                return $this->getZipFile($mosque, $path);
            }
        }

        return null;
    }

    private function getZipFile(Mosque $mosque, $path)
    {
        $zip = new \ZipArchive();
        $zipFileName = $mosque->getSlug() . ".zip";
        $zipFilePath = "$path/" . $zipFileName;

        if ($zip->open($zipFilePath, \ZipArchive::CREATE) === true) {
            $zip->addGlob("$path/*.csv", GLOB_BRACE, array('remove_all_path' => TRUE));
            $zip->close();
            array_map('unlink', glob("$path/*.csv"));
        }
        return $zipFilePath;
    }

    /**
     *  Get pray times and other info of the mosque
     * @param Mosque $mosque
     * @return array
     */
    public function prayTimes(Mosque $mosque)
    {
        $conf = $mosque->getConfiguration();
        $result = [
            'id' => $mosque->getId(),
            'name' => $mosque->getTitle(),
            'localisation' => $mosque->getLocalisation(),
            'phone' => $mosque->getPhone(),
            'email' => $mosque->getEmail(),
            'website' => $mosque->getSite(),
            'association' => $mosque->getAssociationName(),
            'latitude' => $conf->getLatitude(),
            'longitude' => $conf->getLongitude(),
            'jumua' => $conf->getJumuaTime(),
            'shuruk' => null,
            'times' => [],
            'iqama' => $conf->getWaitingTimes()
        ];

        if ($conf->isCalendar()) {
            $times = $this->prayTimesFromCalendar($conf);
        }

        if ($conf->isApi()) {
           $times = $this->prayTimesFromApi($conf);
        }

        $result['shuruk'] = $times[1];
        unset($times[1]);

        $times =  array_values($times);
        $times = $this->fixationProcess($times, $conf);
        $result['times'] = $times;
        return $result;
    }

    private function prayTimesFromCalendar(Configuration $conf)
    {
        $date = new \DateTime();
        $calendar = $conf->getCalendar();
        if (is_array($calendar)) {
            $month = $date->format('m') - 1;
            $day = (int)$date->format('d');
            return $calendar[$month][$day];
        }
        return [];
    }

    private function prayTimesFromApi(Configuration $conf)
    {
        $date = new \DateTime();
        if ($conf->getPrayerMethod() !== Configuration::METHOD_CUSTOM) {
            $this->praytime->setCalcMethod($conf->getPrayerMethod());
        }
        if ($conf->getPrayerMethod() === Configuration::METHOD_CUSTOM) {
            $this->praytime->setFajrAngle($conf->getFajrDegree());
            $this->praytime->setIshaAngle($conf->getIshaDegree());
        }
        $this->praytime->setAsrMethod($conf->getAsrMethod());
        $this->praytime->setHighLatsMethod($conf->getHighLatsMethod());
        $times = $this->praytime->getPrayerTimes($date->getTimestamp(), $conf->getLatitude(), $conf->getLongitude(), $conf->getTimezone());
        unset($times[5]);
        return $times;
    }

    private function fixationProcess(array $times, Configuration $conf)
    {
        $fixations = $conf->getFixedTimes();
        foreach ($fixations as $key => $fixation){
            if(!empty($fixation) && strpos($fixation, "00:") !== 0 && $fixation > $times[$key]){
                $times[$key] = $fixation;
            }
        }
        return $times;
    }
}
