<?php

namespace App\Form\Model;

use App\Entity\Campus;
use Doctrine\DBAL\Types\BooleanType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class SearchOuting
{
    private Campus $campus;
    private $search;
    private DateType $dateStarted;
    private DateType $dateEnded;
    private $isOrganizer;
    private $isRegistered;
    private $isNotRegistered;
    private $isOver;

    /**
     * @return Campus
     */
    public function getCampus(): Campus
    {
        return $this->campus;
    }

    /**
     * @return DateType|null
     */
    public function getDateEnded(): DateType
    {
        return $this->dateEnded;
    }

    /**
     * @return DateType|null
     */
    public function getDateStarted(): DateType
    {
        return $this->dateStarted;
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
     * @param DateType $dateEnded
     */
    public function setDateEnded(DateType $dateEnded): void
    {
        $this->dateEnded = $dateEnded;
    }

    /**
     * @param DateType $dateStarted
     */
    public function setDateStarted(DateType $dateStarted): void
    {
        $this->dateStarted = $dateStarted;
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
