<?php

namespace AppBundle\Service;

use AppBundle\Entity\Configuration;
use AppBundle\Entity\FlashMessage;
use AppBundle\Entity\Message;
use AppBundle\Entity\Mosque;
use AppBundle\Service\Vendor\PrayTime;
use Psr\Log\LoggerInterface;

class PrayerTime
{

    /**
     * @var PrayTime
     */
    private $praytime;

    /**
     * @var LoggerInterface
     */
    private $logger;

    private $cacheDir;

    public function __construct($praytime, LoggerInterface $logger, $cacheDir)
    {
        $this->praytime = $praytime;
        $this->logger = $logger;
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
        return $mosque->getUpdated()->getTimestamp() > $lastUpdatedDate;
    }

    function getCalendar(Mosque $mosque)
    {
        $conf = $mosque->getConfiguration();

        if ($conf->isCalendar()) {
            $calendar = $conf->getCalendar();
            foreach ($calendar as $month => $days) {
                foreach ($days as $day => $prayers) {
                    $timestamp = strtotime(date('Y') . '-' . ($month + 1) . '-' . $day . " 12:00:00");
                    $this->applyDst($prayers, $mosque, $timestamp);
                    $this->fixationProcess($prayers, $conf);
                    $calendar[$month][$day] = $prayers;
                }
            }
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

            foreach (Calendar::MONTHS as $month => $days) {
                for ($day = 1; $day <= $days; $day++) {
                    $timestamp = strtotime(date('Y') . '-' . ($month + 1) . '-' . $day . " 12:00:00");
                    $prayers = $this->praytime->getPrayerTimes($timestamp, $mosque->getLatitude(),
                        $mosque->getLongitude(), $conf->getTimezone());
                    unset($prayers[5]);
                    $this->adjust($prayers, $mosque);
                    $this->applyDst($prayers, $mosque, $timestamp);
                    $this->fixationProcess($prayers, $conf);
                    $calendar[$month][$day] = $prayers;
                }
            }
        }

        return $calendar;
    }

    private function adjust(&$prayers, Mosque $mosque)
    {
        $adjusted = $mosque->getConf()->getAdjustedTimes();
        foreach ($prayers as $k => $prayer) {
            if (empty($adjusted[$k])) {
                continue;
            }

            try {
                $prayers[$k] = ((new \DateTime($prayer))->modify($adjusted[$k] . " minutes"))->format("H:i");
            } catch (\Exception $e) {
                $prayers[$k] = "ERROR";
                $this->logger->error("Erreur de parsing heure de prière", [$e, $mosque->getId()]);
            }
        }
    }

    private function applyDst(&$prayers, Mosque $mosque, $timestamp)
    {
        $conf = $mosque->getConf();
        // dst disabled
        if ($conf->getDst() === 0) {
            return;
        }

        // dst programmed and not between selected dates
        if ($conf->getDst() === 1 && ($conf->getDstWinterDate() === null || $conf->getDstSummerDate() === null)) {
            return;
        }

        if ($conf->getDst() === 1 && ($timestamp < $conf->getDstSummerDate()->getTimestamp() || $timestamp > $conf->getDstWinterDate()->getTimestamp())) {
            return;
        }

        // dst auto and not in effect
        if ($conf->getDst() === 2 && date('I', $timestamp) == 0) {
            return;
        }

        foreach ($prayers as $k => $prayer) {
            try {
                $prayers[$k] = ((new \DateTime($prayer))->modify("+1 hour"))->format("H:i");
            } catch (\Exception $e) {
                $prayers[$k] = "ERROR";
                $this->logger->error("Erreur de parsing heure de prière", [$e, $mosque->getId()]);
            }
        }
    }

    private function fixationProcess(array &$prayers, Configuration $conf)
    {
        $fixations = $conf->getFixedTimes();
        $fixations = [
            1 => $fixations[0],
            2 => null,
            3 => $fixations[1],
            4 => $fixations[2],
            5 => $fixations[3],
            6 => $fixations[4],
        ];

        foreach ($fixations as $k => $fixation) {
            // adjust isha to x min after maghrib if option enabled
            if ($k === 6 && is_numeric($conf->getIshaFixation())) {
                try {
                    $prayers[6] = (new \DateTime($prayers[5]))->modify($conf->getIshaFixation() . "minutes")->format("H:i");;
                } catch (\Exception $e) {
                    $prayers[$k] = "ERROR";
                    $this->logger->error("Erreur de parsing heure de prière", [$e]);
                }
            }

            if (!empty($fixation) && $fixation > $prayers[$k]) {
                $prayers[$k] = $fixation;
            }
        }
        return $prayers;
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
            foreach ($files as $file) {
                if (strpos($file, ".") !== 0) {
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
     * @param bool $returnFullCalendar
     * @return array
     */
    public function prayTimes(Mosque $mosque, $returnFullCalendar = false)
    {
        $conf = $mosque->getConfiguration();
        $flashMessage = $mosque->getFlashMessage();

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
            'jumua' => $conf->isNoJumua() ? null : $conf->getJumuaTime(),
            'jumua2' => $conf->isNoJumua() ? null : $conf->getJumuaTime2(),
            'shuruq' => null,
            'times' => null,
            'fixedIqama' => $conf->getFixedIqama(),
            'iqama' => $conf->getWaitingTimes(),
            'womenSpace' => $mosque->getWomenSpace(),
            'janazaPrayer' => $mosque->getJanazaPrayer(),
            'aidPrayer' => $mosque->getAidPrayer(),
            'childrenCourses' => $mosque->getChildrenCourses(),
            'adultCourses' => $mosque->getAdultCourses(),
            'ramadanMeal' => $mosque->getRamadanMeal(),
            'handicapAccessibility' => $mosque->getHandicapAccessibility(),
            'ablutions' => $mosque->getAblutions(),
            'parking' => $mosque->getParking(),
            'flashMessage' => $flashMessage instanceof FlashMessage && $flashMessage->isAvailable() ? $flashMessage->getContent() : null,
            'announcements' => $this->getMessages($mosque),
            'updatedAt' => $mosque->getUpdated()->getTimestamp(),
        ];

        $calendar = $this->getCalendar($mosque);

        if ($returnFullCalendar) {
            $result['calendar'] = $calendar;
        }

        $times = $this->getPrayTimes($calendar);
        $result['shuruq'] = $times[1];
        unset($times[1]);

        $result['times'] = array_values($times);
        return $result;
    }

    private function getPrayTimes($calendar)
    {
        $date = new \DateTime();
        $month = $date->format('m') - 1;
        $day = (int)$date->format('d');
        return array_values($calendar[$month][$day]);
    }

    private function getMessages(Mosque $mosque)
    {
        $messages = [];
        /**
         * @var Message $message
         */
        foreach ($mosque->getMessages() as $message) {
            if ($message->isEnabled() && $message->isMobile()) {
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
