<?php

namespace AppBundle\Entity;

use Doctrine\ORM\PersistentCollection;
use Symfony\Component\Validator\Constraints as Assert;
use FOS\UserBundle\Model\User as BaseUser;

/**
 * User
 */
class User extends BaseUser
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @Assert\IsTrue(groups={"Registration"})
     * @var boolean
     */
    private $tou;


    /**
     * @var string
     */
    private $apiAccessToken;


    /**
     * @var integer
     */
    private $apiQuota;

    /**
     * @var \DateTime
     */
    private $created;

    /**
     * @var \DateTime
     */
    private $updated;

    /**
     * @var PersistentCollection
     */
    private $mosques;

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     *
     * @return User
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
     * @return User
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
        return $this->updated;
    }

    /**
     *
     * @return boolean
     */
    function isAdmin()
    {
        return $this->hasRole("ROLE_ADMIN");
    }

    /**
     * @return PersistentCollection
     */
    public function getMosques(): PersistentCollection
    {
        return $this->mosques;
    }

    /**
     * @return bool
     */
    public function isTou()
    {
        return $this->tou;
    }

    /**
     * @param bool $tou
     * @return User
     */
    public function setTou(bool $tou): User
    {
        $this->tou = $tou;
        return $this;
    }

    /**
     * @return string
     */
    public function getApiAccessToken(): string
    {
        return $this->apiAccessToken;
    }

    /**
     * @param string $apiAccessToken
     */
    public function setApiAccessToken($apiAccessToken): void
    {
        $this->apiAccessToken = $apiAccessToken;
    }

    /**
     * @return int
     */
    public function getApiQuota(): int
    {
        return $this->apiQuota;
    }

    /**
     * @param int $apiQuota
     */
    public function setApiQuota($apiQuota): void
    {
        $this->apiQuota = $apiQuota;
    }

}

