<?php

namespace App\Form\Model;

use App\Entity\Campus;
use Doctrine\DBAL\Types\BooleanType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class SearchOuting
{
    private Campus $campus;
    private $search;
    private \DateTimeInterface $dateStarted;
    private \DateTimeInterface $dateEnded;
    private bool $isOrganizer;
    private bool $isRegistered;
    private bool $isNotRegistered;
    private bool $isOver;

    public function __construct()
    {

    }

    /**
     * @return Campus
     */
    public function getCampus(): Campus
    {
        return $this->campus;
    }

    /**
     * @return \DateTimeInterface
     */
    public function getDateStarted(): \DateTimeInterface
    {
        return $this->dateStarted;
    }

    /**
     * @return \DateTimeInterface
     */
    public function getDateEnded(): \DateTimeInterface
    {
        return $this->dateEnded;
    }

    /**
     * @return boolean
     */
    public function getIsNotRegistered(): bool
    {
        return $this->isNotRegistered;
    }

    /**
     * @return boolean
     */
    public function getIsOrganizer(): bool
    {
        return $this->isOrganizer;
    }

    /**
     * @return boolean
     */
    public function getIsOver(): bool
    {
        return $this->isOver;
    }

    /**
     * @return boolean
     */
    public function getIsRegistered(): bool
    {
        return $this->isRegistered;
    }

    /**
     * @return mixed
     */
    public function getSearch()
    {
        return $this->search;
    }

    /**
     * @param Campus $campus
     */
    public function setCampus(Campus $campus): void
    {
        $this->campus = $campus;
    }

    /**
     * @param \DateTimeInterface $dateStarted
     */
    public function setDateStarted(\DateTimeInterface $dateStarted): void
    {
        $this->dateStarted = $dateStarted;
    }

    /**
     * @param \DateTimeInterface $dateEnded
     */
    public function setDateEnded(\DateTimeInterface $dateEnded): void
    {
        $this->dateEnded = $dateEnded;
    }

    /**
     * @param mixed $isNotRegistered
     */
    public function setIsNotRegistered($isNotRegistered): void
    {
        $this->isNotRegistered = $isNotRegistered;
    }

    /**
     * @param mixed $isOrganizer
     */
    public function setIsOrganizer($isOrganizer): void
    {
        $this->isOrganizer = $isOrganizer;
    }

    /**
     * @param mixed $isOver
     */
    public function setIsOver($isOver): void
    {
        $this->isOver = $isOver;
    }

    /**
     * @param mixed $isRegistered
     */
    public function setIsRegistered($isRegistered): void
    {
        $this->isRegistered = $isRegistered;
    }

    /**
     * @param mixed $search
     */
    public function setSearch($search): void
    {
        $this->search = $search;
    }


}
