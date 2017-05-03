<?php

namespace AppBundle\Entity;

use AppBundle\Entity\User;
use AppBundle\Entity\Configuration;

/**
 * Mosque
 */
class Mosque {

    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $name;

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
    private $country;

    /**
     * @var string
     */
    private $associationName;

    /**
     * @var string
     */
    private $phone;

    /**
     * @var string
     */
    private $zipcode;

    /**
     * @var string$updated
     */
    private $rib;

    /**
     * @var string
     */
    private $email;

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
     * Get id
     *
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Mosque
     */
    public function setName($name) {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Set name
     *
     * @param string $slug
     *
     * @return Mosque
     */
    public function setSlug($slug) {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getSlug() {
        return $this->slug;
    }

    /**
     * Set address
     *
     * @param string $address
     *
     * @return Mosque
     */
    public function setAddress($address) {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string
     */
    public function getAddress() {
        return $this->address;
    }

    /**
     * Set city
     *
     * @param string $city
     *
     * @return Mosque
     */
    public function setCity($city) {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return string
     */
    public function getCity() {
        return $this->city;
    }

    /**
     * Set country
     *
     * @param string $country
     *
     * @return Mosque
     */
    public function setCountry($country) {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country
     *
     * @return string
     */
    public function getCountry() {
        return $this->country;
    }

    /**
     * Set associationName
     *
     * @param string $associationName
     *
     * @return Mosque
     */
    public function setAssociationName($associationName) {
        $this->associationName = $associationName;

        return $this;
    }

    /**
     * Get associationName
     *
     * @return string
     */
    public function getAssociationName() {
        return $this->associationName;
    }

    /**
     * Set phone
     *
     * @param string $phone
     *
     * @return Mosque
     */
    public function setPhone($phone) {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string
     */
    public function getPhone() {
        return $this->phone;
    }

    /**
     * Set zipcode
     *
     * @param string $zipcode
     *
     * @return Mosque
     */
    public function setZipcode($zipcode) {
        $this->zipcode = $zipcode;

        return $this;
    }

    /**
     * Get zipcode
     *
     * @return string
     */
    public function getZipcode() {
        return $this->zipcode;
    }

    /**
     * Set rib
     *
     * @param string $rib
     *
     * @return Mosque
     */
    public function setRib($rib) {
        $this->rib = $rib;

        return $this;
    }

    /**
     * Get rib
     *
     * @return string
     */
    public function getRib() {
        return $this->rib;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return Mosque
     */
    public function setEmail($email) {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail() {
        return $this->email;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     *
     * @return Mosque
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
     * @param \DateTime $updated
     *
     * @return Mosque
     */
    public function setUpdated($updated) {
        $this->updated = $updated;

        return $this;
    }

    /**
     * Get updated
     *
     * @return \DateTime
     */
    public function getUpdated() {
        return $this->updated;
    }

    function getUser(): User {
        return $this->user;
    }

    function setUser(User $user) {
        $this->user = $user;
    }

    function getConfiguration(): Configuration {
        return $this->configuration;
    }

    function setConfiguration(Configuration $configuration) {
        $this->configuration = $configuration;
    }

}
