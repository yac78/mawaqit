<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Annotation\Groups;


/**
 * Mosque
 */
class Mosque
{
    const STATUS_NEW = "NEW";
    const STATUS_VALIDATED = "VALIDATED";
    const STATUS_SUSPENDED = "SUSPENDED";
    const STATUS_CHECK = "CHECK";
    const STATUS_DUPLICATED = "DUPLICATED";

    const TYPE_MOSQUE = "mosque";
    const TYPE_HOME = "home";

    const TYPES = [
        self::TYPE_MOSQUE, self::TYPE_HOME
    ];

    const STATUSES = [
        self::STATUS_NEW, self::STATUS_CHECK, self::STATUS_VALIDATED, self::STATUS_SUSPENDED, self::STATUS_DUPLICATED
    ];

    /**
     * @Groups({"search"})
     * @var int
     */
    private $id;

    /**
     * @Groups({"search"})
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $type = "mosque";

    /**
     * @var string
     */
    private $slug;

    /**
     * @var string
     */
    private $address;

    /**
     * @var string
     */
    private $city;

    /**
     * @var string
     */
    private $zipcode;

    /**
     * @var string
     */
    private $country;

    /**
     * @var float
     */
    private $latitude;

    /**
     * @var float
     */
    private $longitude;

    /**
     * @var string
     */
    private $countryFullName;

    /**
     * @var string
     */
    private $associationName;

    /**
     * @Groups({"search"})
     * @var string
     */
    private $phone;
    /**
     * @var string
     */
    private $rib;

    /**
     * @Groups({"search"})
     * @var string
     */
    private $email;

    /**
     * @Groups({"search"})
     * @var string
     */
    private $site;

    /**
     * @var string
     */
    private $status = self::STATUS_NEW;

    /**
     * @var boolean
     */
    private $addOnMap = true;

    /**
     * @var File
     */
    private $justificatoryFile;

    /**
     * @var File
     */
    private $file1;

    /**
     * @var File
     */
    private $file2;

    /**
     * @var File
     */
    private $file3;

    /**
     * @var string
     */
    private $justificatory;
    /**
     * @var string
     */
    private $image1;

    /**
     * @var string
     */
    private $image2;

    /**
     * @var string
     */
    private $image3;

    /**
     * The choosen locale (language fr, ar, en, ...)
     * @var string
     */
    private $locale = 'fr';

    /**
     * @var \DateTime
     */
    private $created;

    /**
     * @var \DateTime
     */
    private $updated;

    /**
     * @var User
     */
    private $user;

    /**
     * @var Configuration
     */
    private $configuration;

    /**
     * @var ArrayCollection[Message]
     */
    private $messages;

    /**
     * @var FlashMessage
     */
    private $flashMessage;
    /**
     * @var boolean|null
     */
    private $isCalendarCompleted = null;

    /**
     * @var boolean|null
     */
    private $womenSpace;
    /**
     * @var boolean|null
     */
    private $janazaPrayer;
    /**
     * @var boolean|null
     */
    private $aidPrayer;
    /**
     * @var boolean|null
     */
    private $childrenCourses;
    /**
     * @var boolean|null
     */
    private $adultCourses;
    /**
     * @var boolean|null
     */
    private $ramadanMeal;
    /**
     * @var boolean|null
     */
    private $handicapAccessibility;
    /**
     * @var boolean|null
     */
    private $ablutions;
    /**
     * @var boolean|null
     */
    private $parking;

    /**
     * @var boolean|null
     */
    private $synchronized;

    public function __construct()
    {
        $this->messages = new ArrayCollection();
        $this->configuration = new Configuration();
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Mosque
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set name
     *
     * @param string $slug
     *
     * @return Mosque
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set address
     *
     * @param string $address
     *
     * @return Mosque
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set city
     *
     * @param string $city
     *
     * @return Mosque
     */
    public function setCity($city)
    {
        $transformedCity = [];
        $cityParts = preg_split('/\s+|\-+/', $city);
        foreach ($cityParts as $key => $part) {
            $transformedCity[$key] = ucfirst(strtolower($part));
        }

        $this->city = implode("-", $transformedCity);

        return $this;
    }

    /**
     * Get city
     *
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set country
     *
     * @param string $country
     *
     * @return Mosque
     */
    public function setCountry($country)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country
     *
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @return string
     */
    public function getCountryFullName()
    {
        return $this->countryFullName;
    }

    /**
     * @param string $countryFullName
     * @return Mosque
     */
    public function setCountryFullName(string $countryFullName): Mosque
    {
        $this->countryFullName = $countryFullName;
        return $this;
    }

    /**
     * Set associationName
     *
     * @param string $associationName
     *
     * @return Mosque
     */
    public function setAssociationName($associationName)
    {
        $this->associationName = $associationName;

        return $this;
    }

    /**
     * Get associationName
     *
     * @return string
     */
    public function getAssociationName()
    {
        return $this->associationName;
    }

    /**
     * Set phone
     *
     * @param string $phone
     *
     * @return Mosque
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set site
     *
     * @param string $site
     *
     * @return Mosque
     */
    public function setSite($site)
    {
        $this->site = $site;

        return $this;
    }

    /**
     * Get site
     *
     * @return string
     */
    public function getSite()
    {
        return $this->site;
    }

    /**
     * Set zipcode
     *
     * @param string $zipcode
     *
     * @return Mosque
     */
    public function setZipcode($zipcode)
    {
        $this->zipcode = $zipcode;

        return $this;
    }

    /**
     * Get zipcode
     *
     * @return string
     */
    public function getZipcode()
    {
        return $this->zipcode;
    }

    /**
     * Set rib
     *
     * @param string $rib
     *
     * @return Mosque
     */
    public function setRib($rib)
    {
        $this->rib = $rib;

        return $this;
    }

    /**
     * Get rib
     *
     * @return string
     */
    public function getRib()
    {
        return $this->rib;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return Mosque
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     *
     * @return Mosque
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set updated
     *
     * @param \DateTime $updated
     *
     * @return Mosque
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;

        return $this;
    }

    /**
     * Get updated
     *
     * @return \DateTime
     */
    public function getUpdated()
    {
        $mosqueUpdated = $this->updated;
        $configurationUpdated = $this->configuration->getUpdated();
        if ($mosqueUpdated > $configurationUpdated) {
            return $mosqueUpdated;
        }
        return $configurationUpdated;
    }

    /**
     * Get city + zipcode
     * @Groups({"search"})
     * @return string
     */
    public function getLocalisation()
    {
        return ($this->address ? $this->address . ' ' : '') . $this->zipcode . ' ' . $this->city . ' ' . $this->getCountryFullName();
    }

    function getUser()
    {
        return $this->user;
    }

    function setUser(User $user)
    {
        $this->user = $user;
    }

    function getConfiguration()
    {
        return $this->configuration;
    }

    function setConfiguration(Configuration $configuration)
    {
        $this->configuration = $configuration;
    }

    /**
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $file
     *
     * @return self
     */
    public function setJustificatoryFile(File $file = null)
    {
        $this->justificatoryFile = $file;

        if ($file) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updated = new \DateTime();
        }

        return $this;
    }

    /**
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $image
     *
     * @return self
     */
    public function setFile1(File $image = null)
    {
        $this->file1 = $image;

        if ($image) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updated = new \DateTime();
        }

        return $this;
    }

    /**
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $image
     *
     * @return self
     */
    public function setFile2(File $image = null)
    {
        $this->file2 = $image;

        if ($image) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updated = new \DateTime();
        }

        return $this;
    }

    /**
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $image
     *
     * @return self
     */
    public function setFile3(File $image = null)
    {
        $this->file3 = $image;

        if ($image) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updated = new \DateTime();
        }

        return $this;
    }

    /**
     * @return File|null
     */
    public function getJustificatoryFile()
    {
        return $this->justificatoryFile;
    }

    /**
     * @return string
     */
    public function getJustificatory()
    {
        return $this->justificatory;
    }

    /**
     * @param string $justificatory
     * @return $this
     */
    public function setJustificatory($justificatory)
    {
        $this->justificatory = $justificatory;
        return $this;
    }

    /**
     * @return File|null
     */
    public function getFile1()
    {
        return $this->file1;
    }

    /**
     * @return File|null
     */
    public function getFile2()
    {
        return $this->file2;
    }

    /**
     * @return File|null
     */
    public function getFile3()
    {
        return $this->file3;
    }

    /**
     * @param string $imageName
     *
     * @return self
     */
    public function setImage1($imageName)
    {
        $this->image1 = $imageName;

        return $this;
    }

    /**
     * @param string $imageName
     *
     * @return self
     */
    public function setImage2($imageName)
    {
        $this->image2 = $imageName;

        return $this;
    }

    /**
     * @param string $imageName
     *
     * @return self
     */
    public function setImage3($imageName)
    {
        $this->image3 = $imageName;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getImage1()
    {
        return $this->image1;
    }

    /**
     * @return string|null
     */
    public function getImage2()
    {
        return $this->image2;
    }

    /**
     * @return string|null
     */
    public function getImage3()
    {
        return $this->image3;
    }

    public function getMessages()
    {
        return $this->messages;
    }

    public function getNbOfEnabledMessages()
    {
        $nb = 0;
        foreach ($this->messages as $message) {
            if ($message->isEnabled()) {
                $nb++;
            }
        }
        return $nb;
    }

    public function addMessage(Message $message)
    {
        $this->messages->add($message);
        $message->setMosque($this);
        $this->setUpdated(new \DateTime());
    }

    public function clearMessages()
    {
        $this->messages = null;
    }

    /**
     * True if calendar is completed
     * @return boolean
     */
    function isCalendarCompleted()
    {
        if ($this->isCalendarCompleted === null) {
            $this->isCalendarCompleted = true;
            $configuration = $this->configuration;
            if ($configuration->isCalendar()) {
                if (!empty($configuration->getCalendar())) {
                    foreach ($configuration->getCalendar() as $month => $days) {
                        foreach ($days as $day => $prayers) {
                            foreach ($prayers as $prayerIndex => $prayer) {
                                if (empty($prayer)) {
                                    $this->isCalendarCompleted = false;
                                }
                            }
                        }
                    }
                }
            }
        }
        return $this->isCalendarCompleted;
    }

    /**
     * get GPS coordinates
     * @return array
     */
    function getGpsCoordinates()
    {
        return [
            "lat" => $this->getLatitude(),
            "lon" => $this->getLongitude()
        ];
    }

    /**
     * Get the title to display on top of screen
     * @return string
     */
    function getTitle()
    {
        if ($this->isHome()) {
            return 'homeTitle';
        }

        $name = $this->getName();
        if (strpos(strtolower($name), strtolower($this->getCity())) === false) {
            $name .= " - " . $this->getCity();
        }

        return $name;
    }

    /**
     * @return bool
     */
    public function isAddOnMap()
    {
        return $this->addOnMap;
    }

    /**
     * @param bool $addOnMap
     */
    public function setAddOnMap($addOnMap)
    {
        $this->addOnMap = $addOnMap;
    }

    public function getTypes()
    {
        return self::TYPES;
    }

    /**
     * Set type
     *
     * @param string $type
     *
     * @return Mosque
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    public function resetToHome()
    {
        $this->addOnMap = false;
        $this->address = null;
        $this->site = null;
        $this->phone = null;
        $this->email = null;
        $this->associationName = null;
        $this->rib = null;
        $this->status = Mosque::STATUS_VALIDATED;
    }

    /**
     * @Groups({"search"})
     * @return float
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * @Groups({"search"})
     * @return float
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * @param float $latitude
     */
    public function setLatitude(float $latitude): void
    {
        $this->latitude = $latitude;
    }

    /**
     * @param float $longitude
     */
    public function setLongitude(float $longitude): void
    {
        $this->longitude = $longitude;
    }

    /**
     * @Groups({"search"})
     * @return string
     */
    public function getImage()
    {
        if (empty($this->image1)) {
            return 'https://mawaqit.net/bundles/app/prayer-times/img/default.jpg';
        }

        return "https://mawaqit.net/upload/" . $this->image1;
    }

    /**
     * @Groups({"search"})
     * @return string
     */
    public function getUrl()
    {
        return "https://mawaqit.net/ar/" . $this->slug;
    }

    public function isHome()
    {
        return $this->type === self::TYPE_HOME;
    }

    public function isMosque()
    {
        return $this->type === self::TYPE_MOSQUE;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @param string $status
     * @return Mosque
     */
    public function setStatus(string $status): Mosque
    {
        $this->status = $status;
        return $this;
    }

    public function isValidated()
    {
        return $this->status === self::STATUS_VALIDATED;
    }

    public function statusClass()
    {
        if (!$this->isCalendarCompleted()) {
            return 'calendarIncompleted';
        }
        return strtolower($this->status);
    }

    /**
     * @return string
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * @param string $locale
     */
    public function setLocale(string $locale): void
    {
        $this->locale = $locale;
    }

    /**
     * @return bool|null
     */
    public function getWomenSpace(): ?bool
    {
        return $this->womenSpace;
    }

    /**
     * @param bool|null $womenSpace
     * @return Mosque
     */
    public function setWomenSpace(?bool $womenSpace): Mosque
    {
        $this->womenSpace = $womenSpace;
        return $this;
    }

    /**
     * @return bool|null
     */
    public function getJanazaPrayer(): ?bool
    {
        return $this->janazaPrayer;
    }

    /**
     * @param bool|null $janazaPrayer
     * @return Mosque
     */
    public function setJanazaPrayer(?bool $janazaPrayer): Mosque
    {
        $this->janazaPrayer = $janazaPrayer;
        return $this;
    }

    /**
     * @return bool|null
     */
    public function getChildrenCourses(): ?bool
    {
        return $this->childrenCourses;
    }

    /**
     * @param bool|null $childrenCourses
     * @return Mosque
     */
    public function setChildrenCourses(?bool $childrenCourses): Mosque
    {
        $this->childrenCourses = $childrenCourses;
        return $this;
    }

    /**
     * @return bool|null
     */
    public function getAdultCourses(): ?bool
    {
        return $this->adultCourses;
    }

    /**
     * @param bool|null $adultCourses
     * @return Mosque
     */
    public function setAdultCourses(?bool $adultCourses): Mosque
    {
        $this->adultCourses = $adultCourses;
        return $this;
    }

    /**
     * @return bool|null
     */
    public function getRamadanMeal(): ?bool
    {
        return $this->ramadanMeal;
    }

    /**
     * @param bool|null $ramadanMeal
     * @return Mosque
     */
    public function setRamadanMeal(?bool $ramadanMeal): Mosque
    {
        $this->ramadanMeal = $ramadanMeal;
        return $this;
    }

    /**
     * @return bool|null
     */
    public function getHandicapAccessibility(): ?bool
    {
        return $this->handicapAccessibility;
    }

    /**
     * @param bool|null $handicapAccessibility
     * @return Mosque
     */
    public function setHandicapAccessibility(?bool $handicapAccessibility): Mosque
    {
        $this->handicapAccessibility = $handicapAccessibility;
        return $this;
    }

    /**
     * @return bool|null
     */
    public function getAblutions(): ?bool
    {
        return $this->ablutions;
    }

    /**
     * @param bool|null $ablutions
     * @return Mosque
     */
    public function setAblutions(?bool $ablutions): Mosque
    {
        $this->ablutions = $ablutions;
        return $this;
    }

    /**
     * @return bool|null
     */
    public function getParking(): ?bool
    {
        return $this->parking;
    }

    /**
     * @param bool|null $parking
     * @return Mosque
     */
    public function setParking(?bool $parking): Mosque
    {
        $this->parking = $parking;
        return $this;
    }

    /**
     * @return bool|null
     */
    public function getAidPrayer(): ?bool
    {
        return $this->aidPrayer;
    }

    /**
     * @param bool|null $aidPrayer
     * @return Mosque
     */
    public function setAidPrayer(?bool $aidPrayer): Mosque
    {
        $this->aidPrayer = $aidPrayer;
        return $this;
    }

    public function showUsefullInfo()
    {
        return $this->parking !== null;
    }

    /**
     * @return FlashMessage|null
     */
    public function getFlashMessage(): FlashMessage
    {
        return $this->flashMessage instanceof FlashMessage ? $this->flashMessage : new FlashMessage();
    }

    /**
     * @param FlashMessage $flashMessage
     */
    public function setFlashMessage(FlashMessage $flashMessage = null): void
    {
        $this->flashMessage = $flashMessage;
        $this->setUpdated(new \DateTime());
    }

    /**
     * @return bool|null
     */
    public function getSynchronized(): ?bool
    {
        return $this->synchronized;
    }

    /**
     * @param bool|null $synchronized
     */
    public function setSynchronized(?bool $synchronized): void
    {
        $this->synchronized = $synchronized;
    }

    public function getConf(): Configuration
    {
        return $this->configuration;
    }

}

