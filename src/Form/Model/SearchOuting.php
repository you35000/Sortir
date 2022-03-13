<?php

namespace App\Form\Model;

use App\Entity\Campus;
use DateTimeInterface;
use Doctrine\DBAL\Types\BooleanType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Validator\Constraints as Assert;

class SearchOuting
{
    private ?Campus $campus = null;
    private ?string $search = null;

    /**
     * @Assert\Expression(
     *     "this.getDateStarted() <= this.getDateEnded()",
     *     message="Cette date doit être antérieur")
     */
    private ?DateTimeInterface $dateStarted = null;

    /**
     * @Assert\Expression(
     *     "this.getDateStarted() <= this.getDateEnded()",
     *     message="Cette date doit être postérieur")
     */
    private ?DateTimeInterface $dateEnded = null;
    private ?bool $isOrganizer = null;
    private ?bool $isRegistered = null;
    private ?bool $isNotRegistered = null;
    private ?bool $isOver = null;

    /**
     * @return Campus|null
     */
    public function getCampus(): ?Campus
    {
        return $this->campus;
    }

    /**
     * @return string|null
     */
    public function getSearch(): ?string
    {
        return $this->search;
    }

    /**
     * @return DateTimeInterface|null
     */
    public function getDateStarted(): ?DateTimeInterface
    {
        return $this->dateStarted;
    }

    /**
     * @return DateTimeInterface|null
     */
    public function getDateEnded(): ?DateTimeInterface
    {
        return $this->dateEnded;
    }

    /**
     * @return bool|null
     */
    public function getIsOrganizer(): ?bool
    {
        return $this->isOrganizer;
    }

    /**
     * @return bool|null
     */
    public function getIsRegistered(): ?bool
    {
        return $this->isRegistered;
    }

    /**
     * @return bool|null
     */
    public function getIsNotRegistered(): ?bool
    {
        return $this->isNotRegistered;
    }

    /**
     * @return bool|null
     */
    public function getIsOver(): ?bool
    {
        return $this->isOver;
    }

    /**
     * @param Campus|null $campus
     */
    public function setCampus(?Campus $campus): void
    {
        $this->campus = $campus;
    }

    /**
     * @param string|null $search
     */
    public function setSearch(?string $search): void
    {
        $this->search = $search;
    }

    /**
     * @param DateTimeInterface|null $dateStarted
     */
    public function setDateStarted(?DateTimeInterface $dateStarted): void
    {
        $this->dateStarted = $dateStarted;
    }

    /**
     * @param DateTimeInterface|null $dateEnded
     */
    public function setDateEnded(?DateTimeInterface $dateEnded): void
    {
        $this->dateEnded = $dateEnded;
    }

    /**
     * @param bool|null $isOrganizer
     */
    public function setIsOrganizer(?bool $isOrganizer): void
    {
        $this->isOrganizer = $isOrganizer;
    }

    /**
     * @param bool|null $isRegistered
     */
    public function setIsRegistered(?bool $isRegistered): void
    {
        $this->isRegistered = $isRegistered;
    }

    /**
     * @param bool|null $isNotRegistered
     */
    public function setIsNotRegistered(?bool $isNotRegistered): void
    {
        $this->isNotRegistered = $isNotRegistered;
    }

    /**
     * @param bool|null $isOver
     */
    public function setIsOver(?bool $isOver): void
    {
        $this->isOver = $isOver;
    }


}
