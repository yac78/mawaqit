<?php

namespace AppBundle\Entity;


class FlashMessage
{

    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $content;

    /**
     * @var string
     */
    private $color = "#d9ad0f";

    /**
     * @var string
     */
    private $orientation = "ltr";
    /**
     * @var \DateTime
     */
    private $updated;

    /**
     * @var \DateTime
     */
    private $expire;

    /**
     * @var Mosque
     */
    private $mosque;

    public function __construct()
    {
        $this->expire = new \DateTime("+1 day");
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return FlashMessage
     */
    public function setId(int $id): FlashMessage
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param string|null $content
     * @return FlashMessage
     */
    public function setContent($content): FlashMessage
    {
        $this->content = $content;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * @param \DateTime $updated
     * @return FlashMessage
     */
    public function setUpdated($updated): FlashMessage
    {
        $this->updated = $updated;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getExpire()
    {
        return $this->expire;
    }

    /**
     * @param \DateTime $expire
     * @return FlashMessage
     */
    public function setExpire(\DateTime $expire): FlashMessage
    {
        $expire->setTime(23,59,59);
        $this->expire = $expire;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isExpired()
    {
        $expire = clone  $this->expire;
        $expire->setTimezone(new \DateTimeZone($this->mosque->getConf()->getTimezone()));
        $expire->setTime(23,59,59);
        return $expire < new \DateTime("now", new \DateTimeZone($this->mosque->getConf()->getTimezone()));
    }

    /**
     * @return boolean
     */
    public function isAvailable()
    {
        return $this->content && !$this->isExpired();
    }

    /**
     * @return string
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * @param string $color
     * @return FlashMessage
     */
    public function setColor(string $color): FlashMessage
    {
        $this->color = $color;
        return $this;
    }

    /**
     * @return string
     */
    public function getOrientation(): string
    {
        return $this->orientation;
    }

    /**
     * @param string $orientation
     * @return FlashMessage
     */
    public function setOrientation(string $orientation): FlashMessage
    {
        $this->orientation = $orientation;
        return $this;
    }
}
