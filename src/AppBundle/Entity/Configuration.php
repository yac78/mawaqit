<?php

namespace AppBundle\Entity;

/**
 * Configuration
 */
class Configuration {

    const CUSTOM_CALCUL_CHOICE = 'custom';
    const CSV_CALCUL_CHOICE = 'csv';
    const CALCUL_CHOICE = [
        self::CUSTOM_CALCUL_CHOICE,
        self::CSV_CALCUL_CHOICE
    ];
    const METHOD_ISNA = 'ISNA';
    const PRAYER_TIMES_METHODS = [
        self::METHOD_ISNA
    ];

    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $lang = 'fr';

    /**
     * @var \DateTime
     */
    private $joumouaaTime;

    /**
     * @var \DateTime
     */
    private $aidTime;

    /**
     * @var int
     */
    private $imsakNbMinBeforeSobh = 0;

    /**
     * @var \DateTime
     */
    private $minimumIchaTime;

    /**
     * @var \DateTime
     */
    private $maximumIchaTimeForNoWaiting;

    /**
     * @var array
     */
    private $prayersWaitingTimes;

    /**
     * @var array
     */
    private $prayerTimesAdjustment = [0, 0, 0, 0, 0];

    /**
     * @var array
     */
    private $prayerTimesFixing;

    /**
     * @var int
     */
    private $hijriAdjustment = 0;

    /**
     * @var bool
     */
    private $hijriDateEnabled = true;

    /**
     * @var bool
     */
    private $douaaAfterAdhanEnabled = true;

    /**
     * @var bool
     */
    private $douaaAfterPrayerEnabled = true;

    /**
     * @var bool
     */
    private $androidAppEnabled = false;

    /**
     * @var string
     */
    private $calculChoice = self::CUSTOM_CALCUL_CHOICE;

    /**
     * @var string
     */
    private $prayerMethod = self::METHOD_ISNA;

    /**
     * @var int
     */
    private $latitude;

    /**
     * @var int
     */
    private $longitude;

    /**
     * @var int
     */
    private $fajrDegree;

    /**
     * @var int
     */
    private $ichaaDegree;

    /**
     * @var int
     */
    private $iqamaDisplayTime = 45;

    /**
     * @var int
     */
    private $adhanDouaaDisplayTime = 30;

    /**
     * @var string
     */
    private $site;

    /**
     * @var string
     */
    private $prayerTimeSite;

    /**
     * @var \DateTime
     */
    private $created;

    /**
     * @var \DateTime
     */
    private $updated;

    /**
     * Get id
     *
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set lang
     *
     * @param string $lang
     *
     * @return Configuration
     */
    public function setLang($lang) {
        $this->lang = $lang;

        return $this;
    }

    /**
     * Get lang
     *
     * @return string
     */
    public function getLang() {
        return $this->lang;
    }

    /**
     * Set joumouaaTime
     *
     * @param \DateTime $joumouaaTime
     *
     * @return Configuration
     */
    public function setJoumouaaTime($joumouaaTime) {
        $this->joumouaaTime = $joumouaaTime;

        return $this;
    }

    /**
     * Get joumouaaTime
     *
     * @return \DateTime
     */
    public function getJoumouaaTime() {
        return $this->joumouaaTime;
    }

    /**
     * Set aidTime
     *
     * @param \DateTime $aidTime
     *
     * @return Configuration
     */
    public function setAidTime($aidTime) {
        $this->aidTime = $aidTime;

        return $this;
    }

    /**
     * Get aidTime
     *
     * @return \DateTime
     */
    public function getAidTime() {
        return $this->aidTime;
    }

    /**
     * Set imsakNbMinBeforeSobh
     *
     * @param integer $imsakNbMinBeforeSobh
     *
     * @return Configuration
     */
    public function setImsakNbMinBeforeSobh($imsakNbMinBeforeSobh) {
        $this->imsakNbMinBeforeSobh = $imsakNbMinBeforeSobh;

        return $this;
    }

    /**
     * Get imsakNbMinBeforeSobh
     *
     * @return int
     */
    public function getImsakNbMinBeforeSobh() {
        return $this->imsakNbMinBeforeSobh;
    }

    /**
     * Set minimumIchaTime
     *
     * @param \DateTime $minimumIchaTime
     *
     * @return Configuration
     */
    public function setMinimumIchaTime($minimumIchaTime) {
        $this->minimumIchaTime = $minimumIchaTime;

        return $this;
    }

    /**
     * Get minimumIchaTime
     *
     * @return \DateTime
     */
    public function getMinimumIchaTime() {
        return $this->minimumIchaTime;
    }

    /**
     * Set maximumIchaTimeForNoWaiting
     *
     * @param \DateTime $maximumIchaTimeForNoWaiting
     *
     * @return Configuration
     */
    public function setMaximumIchaTimeForNoWaiting($maximumIchaTimeForNoWaiting) {
        $this->maximumIchaTimeForNoWaiting = $maximumIchaTimeForNoWaiting;

        return $this;
    }

    /**
     * Get maximumIchaTimeForNoWaiting
     *
     * @return \DateTime
     */
    public function getMaximumIchaTimeForNoWaiting() {
        return $this->maximumIchaTimeForNoWaiting;
    }

    /**
     * Set prayersWaitingTimes
     *
     * @param array $prayersWaitingTimes
     *
     * @return Configuration
     */
    public function setPrayersWaitingTimes($prayersWaitingTimes) {
        $this->prayersWaitingTimes = $prayersWaitingTimes;

        return $this;
    }

    /**
     * Get prayersWaitingTimes
     *
     * @return array
     */
    public function getPrayersWaitingTimes() {
        return $this->prayersWaitingTimes;
    }

    /**
     * Set prayerTimesAdjustment
     *
     * @param array $prayerTimesAdjustment
     *
     * @return Configuration
     */
    public function setPrayerTimesAdjustment($prayerTimesAdjustment) {
        $this->prayerTimesAdjustment = $prayerTimesAdjustment;

        return $this;
    }

    /**
     * Get prayerTimesAdjustment
     *
     * @return array
     */
    public function getPrayerTimesAdjustment() {
        return $this->prayerTimesAdjustment;
    }

    /**
     * Set prayerTimesFixing
     *
     * @param array $prayerTimesFixing
     *
     * @return Configuration
     */
    public function setPrayerTimesFixing($prayerTimesFixing) {
        $this->prayerTimesFixing = $prayerTimesFixing;

        return $this;
    }

    /**
     * Get prayerTimesFixing
     *
     * @return array
     */
    public function getPrayerTimesFixing() {
        return $this->prayerTimesFixing;
    }

    /**
     * Set hijriAdjustment
     *
     * @param integer $hijriAdjustment
     *
     * @return Configuration
     */
    public function setHijriAdjustment($hijriAdjustment) {
        $this->hijriAdjustment = $hijriAdjustment;

        return $this;
    }

    /**
     * Get hijriAdjustment
     *
     * @return int
     */
    public function getHijriAdjustment() {
        return $this->hijriAdjustment;
    }

    /**
     * Set hijriDateEnabled
     *
     * @param boolean $hijriDateEnabled
     *
     * @return Configuration
     */
    public function setHijriDateEnabled($hijriDateEnabled) {
        $this->hijriDateEnabled = $hijriDateEnabled;

        return $this;
    }

    /**
     * Get hijriDateEnabled
     *
     * @return bool
     */
    public function getHijriDateEnabled() {
        return $this->hijriDateEnabled;
    }

    /**
     * Set douaaAfterAdhanEnabled
     *
     * @param boolean $douaaAfterAdhanEnabled
     *
     * @return Configuration
     */
    public function setDouaaAfterAdhanEnabled($douaaAfterAdhanEnabled) {
        $this->douaaAfterAdhanEnabled = $douaaAfterAdhanEnabled;

        return $this;
    }

    /**
     * Get douaaAfterAdhanEnabled
     *
     * @return bool
     */
    public function getDouaaAfterAdhanEnabled() {
        return $this->douaaAfterAdhanEnabled;
    }

    /**
     * Set douaaAfterPrayerEnabled
     *
     * @param boolean $douaaAfterPrayerEnabled
     *
     * @return Configuration
     */
    public function setDouaaAfterPrayerEnabled($douaaAfterPrayerEnabled) {
        $this->douaaAfterPrayerEnabled = $douaaAfterPrayerEnabled;

        return $this;
    }

    /**
     * Get douaaAfterPrayerEnabled
     *
     * @return bool
     */
    public function getDouaaAfterPrayerEnabled() {
        return $this->douaaAfterPrayerEnabled;
    }

    /**
     * Set androidAppEnabled
     *
     * @param boolean $androidAppEnabled
     *
     * @return Configuration
     */
    public function setAndroidAppEnabled($androidAppEnabled) {
        $this->androidAppEnabled = $androidAppEnabled;

        return $this;
    }

    /**
     * Get androidAppEnabled
     *
     * @return bool
     */
    public function getAndroidAppEnabled() {
        return $this->androidAppEnabled;
    }

    /**
     * Set calculChoice
     *
     * @param string $calculChoice
     *
     * @return Configuration
     */
    public function setCalculChoice($calculChoice) {
        $this->calculChoice = $calculChoice;

        return $this;
    }

    /**
     * Get calculChoice
     *
     * @return string
     */
    public function getCalculChoice() {
        return $this->calculChoice;
    }

    /**
     * Set prayerMethod
     *
     * @param string $prayerMethod
     *
     * @return Configuration
     */
    public function setPrayerMethod($prayerMethod) {
        $this->prayerMethod = $prayerMethod;

        return $this;
    }

    /**
     * Set latitude
     *
     * @param integer $latitude
     *
     * @return Mosque
     */
    public function setLatitude($latitude) {
        $this->latitude = $latitude;

        return $this;
    }

    /**
     * Get latitude
     *
     * @return int
     */
    public function getLatitude() {
        return $this->latitude;
    }

    /**
     * Set longitude
     *
     * @param integer $longitude
     *
     * @return Mosque
     */
    public function setLongitude($longitude) {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * Get longitude
     *
     * @return int
     */
    public function getLongitude() {
        return $this->longitude;
    }

    /**
     * Get prayerMethod
     *
     * @return string
     */
    public function getPrayerMethod() {
        return $this->prayerMethod;
    }

    /**
     * Set fajrDegree
     *
     * @param integer $fajrDegree
     *
     * @return Configuration
     */
    public function setFajrDegree($fajrDegree) {
        $this->fajrDegree = $fajrDegree;

        return $this;
    }

    /**
     * Get fajrDegree
     *
     * @return int
     */
    public function getFajrDegree() {
        return $this->fajrDegree;
    }

    /**
     * Set ichaaDegree
     *
     * @param integer $ichaaDegree
     *
     * @return Configuration
     */
    public function setIchaaDegree($ichaaDegree) {
        $this->ichaaDegree = $ichaaDegree;

        return $this;
    }

    /**
     * Get ichaaDegree
     *
     * @return int
     */
    public function getIchaaDegree() {
        return $this->ichaaDegree;
    }

    /**
     * Set iqamaDisplayTime
     *
     * @param integer $iqamaDisplayTime
     *
     * @return Configuration
     */
    public function setIqamaDisplayTime($iqamaDisplayTime) {
        $this->iqamaDisplayTime = $iqamaDisplayTime;

        return $this;
    }

    /**
     * Get iqamaDisplayTime
     *
     * @return int
     */
    public function getIqamaDisplayTime() {
        return $this->iqamaDisplayTime;
    }

    /**
     * Set adhanDouaaDisplayTime
     *
     * @param integer $adhanDouaaDisplayTime
     *
     * @return Configuration
     */
    public function setAdhanDouaaDisplayTime($adhanDouaaDisplayTime) {
        $this->adhanDouaaDisplayTime = $adhanDouaaDisplayTime;

        return $this;
    }

    /**
     * Get adhanDouaaDisplayTime
     *
     * @return int
     */
    public function getAdhanDouaaDisplayTime() {
        return $this->adhanDouaaDisplayTime;
    }

    /**
     * Set site
     *
     * @param string $site
     *
     * @return Configuration
     */
    public function setSite($site) {
        $this->site = $site;

        return $this;
    }

    /**
     * Get site
     *
     * @return string
     */
    public function getSite() {
        return $this->site;
    }

    /**
     * Set prayerTimeSite
     *
     * @param string $prayerTimeSite
     *
     * @return Configuration
     */
    public function setPrayerTimeSite($prayerTimeSite) {
        $this->prayerTimeSite = $prayerTimeSite;

        return $this;
    }

    /**
     * Get prayerTimeSite
     *
     * @return string
     */
    public function getPrayerTimeSite() {
        return $this->prayerTimeSite;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     *
     * @return Configuration
     */
    public function setCreated($created) {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime
     */
    public function getCreated() {
        return $this->created;
    }

    /**
     * Set updated
     *
     * @param string $updated
     *
     * @return Configuration
     */
    public function setUpdated($updated) {
        $this->updated = $updated;

        return $this;
    }

    /**
     * Get updated
     *
     * @return string
     */
    public function getUpdated() {
        return $this->updated;
    }

}
