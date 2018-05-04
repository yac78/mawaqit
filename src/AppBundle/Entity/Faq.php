<?php

namespace AppBundle\Entity;

/**
 * Faq
 */
class Faq
{

    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $question;

    /**
     * @var string
     */
    private $answer;

    /**
     * @var boolean
     */
    private $enabled = true;

    /**
     * @var integer
     */
    private $position;

    /**
     * @var string
     */
    private $slug;

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
     * @return string
     */
    public function getQuestion()
    {
        return $this->question;
    }

    /**
     * @param string $question
     * @return Faq
     */
    public function setQuestion(string $question): Faq
    {
        $this->question = $question;
        return $this;
    }

    /**
     * @return string
     */
    public function getAnswer()
    {
        return $this->answer;
    }

    /**
     * @param string $answer
     * @return Faq
     */
    public function setAnswer(string $answer): Faq
    {
        $this->answer = $answer;
        return $this;
    }

    /**
     * @return bool
     */
    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    /**
     * @param bool $enabled
     * @return Faq
     */
    public function setEnabled(bool $enabled): Faq
    {
        $this->enabled = $enabled;
        return $this;
    }

    /**
     * @return string
     */
    public function getSlug(): string
    {
        return $this->slug;
    }

    /**
     * @param string $slug
     * @return Faq
     */
    public function setSlug(string $slug): Faq
    {
        $this->slug = $slug;
        return $this;
    }

    /**
     * @return int
     */
    public function getPosition(): int
    {
        return $this->position;
    }

    /**
     * @param int $position
     * @return Faq
     */
    public function setPosition(int $position): Faq
    {
        $this->position = $position;
        return $this;
    }

}
