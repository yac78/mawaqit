<?php

namespace AppBundle\Entity;

/**
 * Comment
 */
class Comment
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $text;

    /**
     * @var User
     */
    private $user;

    /**
     * @var Mosque
     */
    private $mosque;

    /**
     * @var \DateTime
     */
    private $createdAt;

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set text.
     *
     * @param string $text
     *
     * @return Comment
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Get text.
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Set user.
     *
     * @param User $user
     *
     * @return Comment
     */
    public function setUser(User $user):self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user.
     *
     * @return User
     */
    public function getUser():User
    {
        return $this->user;
    }

    /**
     * @return Mosque
     */
    public function getMosque(): Mosque
    {
        return $this->mosque;
    }

    /**
     * @param Mosque $mosque
     *
     * @return Comment
     */
    public function setMosque(?Mosque $mosque): Comment
    {
        $this->mosque = $mosque;
        return $this;
    }

    /**
     * Get createdAt.
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function __construct()
    {
        $this->createdAt = new \DateTime();
    }

    public function __toString()
    {
        return substr($this->text, 0, 30) . '...';
    }
}
