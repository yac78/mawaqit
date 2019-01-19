<?php

namespace AppBundle\Service;

use AppBundle\Entity\Configuration;
use AppBundle\Entity\Message;
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

    function getCalendar(Mosque $mosque)
    {
        $conf = $mosque->getConfiguration();

        if ($conf->isCalendar()) {
            $calendar = $conf->getCalendar();
        }

        if ($conf->isApi()) {
            $calendar = [];
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
                for ($day = 1; $day <= $days; $day++) {
                    $date = strtotime(date('Y') . '-' . ($monthIndex + 1) . '-' . $day);
                    $prayers = $this->praytime->getPrayerTimes($date, $mosque->getLatitude(), $mosque->getLongitude(), $conf->getTimezone());
                    unset($prayers[5]);
                    $calendar[$monthIndex][$day] = $prayers;
                }
            }
        }
        return $calendar;
    }


    /**
     * transforme json calendar in csv files and compress theme in a zip file
     * @param Mosque $mosque
     * @return string the path of the zip file
     */
    function getFilesFromCalendar(Mosque $mosque)
    {
        $calendar = $this->getCalendar($mosque);

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

        return null;
    }

    private function getZipFile(Mosque $mosque, $path)
    {
        $zip = new \ZipArchive();
        $zipFileName = $mosque->getSlug() . ".zip";
        $zipFilePath = "$path/" . $zipFileName;

        if ($zip->open($zipFilePath, \ZipArchive::CREATE) === true) {
            $files = scandir("$path");
            foreach($files as $file) {
                if(strpos($file, ".") !== 0 ){
                    $zip->addFile("$path/$file", $file);
                }
            }
            $zip->close();
            array_map('unlink', glob("$path/*.csv"));
        }
        return $zipFilePath;
    }

    /**
     *  Get pray times and other info of the mosque
     * @param Mosque $mosque
     * @param bool $calendar
     * @return array
     */
    public function prayTimes(Mosque $mosque, $calendar = false)
    {
        $conf = $mosque->getConfiguration();
        $result = [
            'id' => $mosque->getId(),
            'name' => $mosque->getTitle(),
            'localisation' => $mosque->getLocalisation(),
            'phone' => $mosque->getPhone(),
            'email' => $mosque->getEmail(),
            'site' => $mosque->getSite(),
            'association' => $mosque->getAssociationName(),
            'image' => $mosque->getImage1() ? 'https://mawaqit.net/upload/' . $mosque->getImage1() : null,
            'url' => 'https://mawaqit.net/fr/' . $mosque->getSlug(),
            'latitude' => $mosque->getLatitude(),
            'longitude' => $mosque->getLongitude(),
            'hijriAdjustment' => $conf->getHijriAdjustment(),
            'jumua' => $conf->getJumuaTime(),
            'jumua2' => $conf->getJumuaTime2(),
            'shuruq' => null,
            'times' => null,
            'fixedTimes' => $conf->getFixedTimes(),
            'fixedIqama' => $conf->getFixedIqama(),
            'iqama' => $conf->getWaitingTimes(),
            'flashMessage' => $mosque->getFlashMessage()->isAvailable() ? $mosque->getFlashMessage()->getContent() : null,
            'announcements' => $this->getMessages($mosque),
            'updatedAt' => $mosque->getUpdated(),
        ];

        if ($calendar) {
            $result['calendar'] = $this->getCalendar($mosque);
        }

        $times = $this->getPrayTimes($mosque);
        $result['shuruq'] = $times[1];
        unset($times[1]);

        $times = $this->fixationProcess($times, $conf);
        $result['times'] = $times;
        return $result;
    }

    private function getPrayTimes(Mosque $mosque)
    {
        $date = new \DateTime();
        $calendar = $this->getCalendar($mosque);
        if (is_array($calendar)) {
            $month = $date->format('m') - 1;
            $day = (int)$date->format('d');
            return array_values($calendar[$month][$day]);
        }
        return [];
    }

    private function fixationProcess(array $times, Configuration $conf)
    {
        $times = array_values($times);
        $fixations = $conf->getFixedTimes();
        foreach ($fixations as $key => $fixation) {
            if (!empty($fixation) && strpos($fixation, "00:") !== 0 && $fixation > $times[$key]) {
                $times[$key] = $fixation;
            }
        }
        return $times;
    }

    private function getMessages(Mosque $mosque)
    {
        $messages = [];
        /**
         * @var Message $message
         */
        foreach ($mosque->getMessages() as $message) {
            if ($message->isEnabled()) {
                $messages[] = [
                    'id' => $message->getId(),
                    'title' => $message->getTitle(),
                    'content' => $message->getContent(),
                    'isMobile' => $message->isMobile(),
                    'isDesktop' => $message->isDesktop(),
                    'image' => $message->getImage() ? 'https://mawaqit.net/upload/' . $message->getImage() : null,
                ];
            }
        }
        return $messages;
    }
}
