<?php

namespace AppBundle\Entity;

use Symfony\Component\HttpFoundation\File\File;
use Doctrine\ORM\Mapping as ORM;

/**
 * Message
 */
class Message
{

    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $content;

    /**
     * @var boolean
     */
    private $enabled = true;

    /**
     * Display on mobile
     * @var boolean
     */
    private $mobile = true;


    /**
     * Display on main mosque screen
     * @var boolean
     */
    private $desktop = true;

    /**
     * @var integer
     */
    private $position;

    /**
     * @var File
     */
    private $file;

    /**
     * @var string
     */
    private $image;

    /**
     * @var \DateTime
     */
    private $updated;

    /**
     * @var Mosque
     */
    private $mosque;

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
     * Set title
     *
     * @param string $title
     *
     * @return Message
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set content
     *
     * @param string $content
     *
     * @return Message
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    function isEnabled()
    {
        return $this->enabled;
    }

    function setEnabled($enabled)
    {
        $this->enabled = $enabled;
    }

    /**
     * @param File|null $image
     * @return $this
     * @throws \Exception
     */
    public function setFile(File $image = null)
    {
        $this->file = $image;

        if ($image) {
            $this->updated = new \DateTimeImmutable();
        }

        return $this;
    }

    /**
     * @return File|null
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param string $imageName
     *
     * @return self
     */
    public function setImage($imageName)
    {
        $this->image = $imageName;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getImage()
    {
        return $this->image;
    }

    function getMosque(): Mosque
    {
        return $this->mosque;
    }

    function setMosque(Mosque $mosque)
    {
        $this->mosque = $mosque;
    }

    /**
     * @return int
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * @param int $position
     * @return self
     */
    public function setPosition($position)
    {
        $this->position = $position;
        return $this;
    }

    /**
     * @return bool
     */
    public function isMobile(): bool
    {
        return $this->mobile;
    }

    /**
     * @param bool $mobile
     * @return Message
     */
    public function setMobile(bool $mobile): Message
    {
        $this->mobile = $mobile;
        return $this;
    }

    /**
     * @return bool
     */
    public function isDesktop(): bool
    {
        return $this->desktop;
    }

    /**
     * @param bool $desktop
     */
    public function setDesktop(bool $desktop): void
    {
        $this->desktop = $desktop;
    }

}
